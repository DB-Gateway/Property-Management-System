<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Dealer;
use App\Support\DealerData;
use App\Support\SimpleXlsxWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PharData;
use RecursiveIteratorIterator;
use Shuchkin\SimpleXLSX;
use Throwable;

class DealerSpreadsheet
{
    public function export(iterable $dealers): string
    {
        $rows = (function () use ($dealers) {
            foreach ($dealers as $dealer) {
                yield array_map(fn ($field) => $dealer->{$field}, array_keys(DealerData::HEADERS));
            }
        })();

        return SimpleXlsxWriter::table(array_values(DealerData::HEADERS), $rows, 'Dealers', [14, 32, 55, 22, 22, 22, 28, 22, 28, 22]);
    }

    public function import(string $path, bool $updateExisting = false): array
    {
        // Validate the entire worksheet before saving any records.
        try {
            $rows = $this->read($path);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw ValidationException::withMessages(['file' => 'The workbook could not be read. Upload a valid, unencrypted .xlsx file using the dealer template.']);
        }

        return DB::transaction(function () use ($rows, $updateExisting) {
            $counts = ['created' => 0, 'updated' => 0, 'skipped' => 0];
            foreach ($rows as $data) {
                $dealer = Dealer::where('source_no', $data['source_no'])->lockForUpdate()->first();
                if ($dealer && ! $updateExisting) {
                    $counts['skipped']++;

                    continue;
                }

                if ($dealer) {
                    $dealer->update($data);
                    $counts['updated']++;
                } else {
                    Dealer::create($data);
                    $counts['created']++;
                }
            }

            AuditLog::record('dealers_imported', "Imported dealers: {$counts['created']} created, {$counts['updated']} updated, {$counts['skipped']} skipped.", null, $counts);

            return $counts;
        });
    }

    private function read(string $path): array
    {
        // Check expanded size without extracting files or inflating workbook XML.
        $archive = new PharData($path);
        $expandedBytes = 0;
        $entries = 0;
        foreach (new RecursiveIteratorIterator($archive) as $entry) {
            $expandedBytes += $entry->getSize();
            if (++$entries > 1000 || $expandedBytes > 25 * 1024 * 1024) {
                throw ValidationException::withMessages(['file' => 'The workbook is too large when expanded. Use a smaller copy of the dealer template.']);
            }
        }

        $workbook = SimpleXLSX::parseFile($path);
        if (! $workbook) {
            throw ValidationException::withMessages(['file' => 'The workbook could not be read. Upload a valid, unencrypted .xlsx file using the dealer template.']);
        }

        [$columns, $rowCount] = $workbook->dimension();
        if ($columns > 50 || $rowCount > 5001) {
            throw ValidationException::withMessages(['file' => 'The first worksheet must contain at most 5,000 data rows and 50 columns.']);
        }

        $mapping = [];
        $records = [];
        $errors = [];
        $seen = [];
        foreach ($workbook->readRowsEx(0, 5002) as $index => $cells) {
            if ($index > 5000 || count($cells) > 50) {
                throw ValidationException::withMessages(['file' => 'The first worksheet must contain at most 5,000 data rows and 50 columns.']);
            }
            if ($index === 0) {
                $mapping = $this->mapHeaders(array_column($cells, 'value'));

                continue;
            }

            $rowNumber = $index + 1;
            $data = [];
            $hasFormula = false;
            foreach ($mapping as $field => $column) {
                $cell = $cells[$column] ?? [];
                $value = trim((string) ($cell['value'] ?? ''));
                $data[$field] = $value === '' ? null : $value;
                $hasFormula = $hasFormula || ($cell['f'] ?? '') !== '';
            }

            if ($hasFormula) {
                $errors[] = "Row {$rowNumber}: use plain values instead of formulas.";
            } elseif (count(array_filter($data, fn ($value) => $value !== null)) === 0) {
                continue;
            } else {
                $validator = Validator::make($data, DealerData::rules(), [], DealerData::HEADERS);
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $message) {
                        $errors[] = "Row {$rowNumber}: {$message}";
                    }
                } else {
                    $number = (int) $data['source_no'];
                    if (isset($seen[$number])) {
                        $errors[] = "Row {$rowNumber}: dealer number {$number} is repeated (first used in row {$seen[$number]}).";
                    } else {
                        $seen[$number] = $rowNumber;
                        $records[] = $data;
                    }
                }
            }

            if (count($errors) >= 50) {
                $errors = array_slice($errors, 0, 50);
                $errors[] = 'Only the first 50 errors are shown. Correct the workbook and try again.';
                break;
            }
        }

        if ($errors) {
            throw ValidationException::withMessages(['file' => array_merge(['No dealers were imported. Please correct these rows:'], $errors)]);
        }
        if (! $records) {
            throw ValidationException::withMessages(['file' => 'The first worksheet contains no dealer records. Fill in the template starting at row 2.']);
        }

        return $records;
    }

    private function mapHeaders(array $headers): array
    {
        $normalize = fn ($value) => preg_replace('/[^a-z0-9]/', '', strtolower(trim((string) $value)));
        $normalized = array_map($normalize, $headers);
        $mapping = [];
        foreach (DealerData::HEADERS as $field => $label) {
            $matches = array_keys(array_filter($normalized, fn ($value) => in_array($value, [$normalize($label), $normalize($field)], true)));
            if (count($matches) !== 1) {
                throw ValidationException::withMessages(['file' => "The first row must contain exactly one '{$label}' column. Use the downloadable dealer template."]);
            }
            $mapping[$field] = $matches[0];
        }

        return $mapping;
    }
}
