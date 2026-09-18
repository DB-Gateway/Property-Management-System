<?php

namespace App\Support;

use DateTimeInterface;

class SimpleXlsxWriter
{
    /** Create an import-friendly table with a header in row 1 and text-formatted columns. */
    public static function table(array $headers, iterable $rows, string $sheetName = 'Dealers', array $widths = []): string
    {
        $lastColumn = self::columnName(count($headers));
        $headerCells = [];
        $columns = [];
        foreach (array_values($headers) as $index => $header) {
            $column = $index + 1;
            $headerCells[] = self::stringCell(self::columnName($column).'1', $header, 5);
            $columns[] = '<col min="'.$column.'" max="'.$column.'" width="'.($widths[$index] ?? 24).'" customWidth="1" style="7"/>';
        }
        $sheetRows = [self::row(1, $headerCells, 28)];
        $rowNumber = 1;
        foreach ($rows as $values) {
            $rowNumber++;
            $cells = [];
            foreach (array_values($values) as $index => $value) {
                $coordinate = self::columnName($index + 1).$rowNumber;
                $cells[] = is_int($value) || is_float($value)
                    ? self::numberCell($coordinate, $value, 6)
                    : self::stringCell($coordinate, self::displayValue($value), 7);
            }
            $sheetRows[] = self::row($rowNumber, $cells);
        }

        $worksheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<dimension ref="A1:'.$lastColumn.$rowNumber.'"/>'
            .'<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            .'<sheetFormatPr defaultRowHeight="30"/>'
            .'<cols>'.implode('', $columns).'</cols><sheetData>'.implode('', $sheetRows).'</sheetData>'
            .'<autoFilter ref="A1:'.$lastColumn.$rowNumber.'"/></worksheet>';

        return self::zip([
            '[Content_Types].xml' => self::contentTypes(),
            '_rels/.rels' => self::rootRelationships(),
            'docProps/app.xml' => self::appProperties(),
            'docProps/core.xml' => self::coreProperties($sheetName),
            'xl/workbook.xml' => self::workbook($sheetName),
            'xl/_rels/workbook.xml.rels' => self::workbookRelationships(),
            'xl/styles.xml' => self::styles(),
            'xl/worksheets/sheet1.xml' => $worksheet,
        ]);
    }

    /**
     * Create a small, standards-compliant XLSX workbook without requiring a PHP ZIP extension.
     *
     * @param  array<string, int>  $summary
     * @param  list<string>  $headers
     * @param  iterable<array<int, scalar|null>>  $rows
     */
    public static function make(string $title, string $subtitle, array $summary, array $headers, iterable $rows): string
    {
        $rows = is_array($rows) ? $rows : iterator_to_array($rows);
        $lastColumn = self::columnName(count($headers));
        $headerRow = 7;
        $lastRow = $headerRow + count($rows);

        $sheetRows = [];
        $sheetRows[] = self::row(1, [self::stringCell('A1', $title, 1)], 27);
        $sheetRows[] = self::row(2, [self::stringCell('A2', $subtitle, 2)]);

        $summaryLabels = [];
        $summaryValues = [];
        $column = 1;
        foreach ($summary as $label => $value) {
            $summaryLabels[] = self::stringCell(self::columnName($column).'4', $label, 3);
            $summaryValues[] = self::numberCell(self::columnName($column).'5', $value, 4);
            $column++;
        }
        $sheetRows[] = self::row(4, $summaryLabels);
        $sheetRows[] = self::row(5, $summaryValues, 22);

        $headerCells = [];
        foreach ($headers as $index => $header) {
            $headerCells[] = self::stringCell(self::columnName($index + 1).$headerRow, $header, 5);
        }
        $sheetRows[] = self::row($headerRow, $headerCells, 23);

        foreach ($rows as $rowIndex => $values) {
            $excelRow = $headerRow + $rowIndex + 1;
            $cells = [];
            foreach (array_values($values) as $cellIndex => $value) {
                $coordinate = self::columnName($cellIndex + 1).$excelRow;
                $cells[] = is_int($value) || is_float($value)
                    ? self::numberCell($coordinate, $value, 6)
                    : self::stringCell($coordinate, self::displayValue($value), 6);
            }
            $sheetRows[] = self::row($excelRow, $cells);
        }

        $worksheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<dimension ref="A1:'.$lastColumn.max($lastRow, $headerRow).'"/>'
            .'<sheetViews><sheetView workbookViewId="0"><pane ySplit="7" topLeftCell="A8" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            .'<sheetFormatPr defaultRowHeight="15"/>'
            .'<cols>'
            .'<col min="1" max="1" width="22" customWidth="1"/><col min="2" max="3" width="14" customWidth="1"/>'
            .'<col min="4" max="4" width="27" customWidth="1"/><col min="5" max="5" width="18" customWidth="1"/>'
            .'<col min="6" max="6" width="25" customWidth="1"/><col min="7" max="8" width="15" customWidth="1"/>'
            .'<col min="9" max="11" width="24" customWidth="1"/><col min="12" max="12" width="18" customWidth="1"/>'
            .'<col min="13" max="14" width="15" customWidth="1"/><col min="15" max="17" width="22" customWidth="1"/>'
            .'<col min="18" max="21" width="19" customWidth="1"/><col min="22" max="22" width="48" customWidth="1"/>'
            .'</cols><sheetData>'.implode('', $sheetRows).'</sheetData>'
            .'<autoFilter ref="A'.$headerRow.':'.$lastColumn.max($lastRow, $headerRow).'"/>'
            .'<mergeCells count="2"><mergeCell ref="A1:'.$lastColumn.'1"/><mergeCell ref="A2:'.$lastColumn.'2"/></mergeCells>'
            .'<pageMargins left="0.25" right="0.25" top="0.5" bottom="0.5" header="0.2" footer="0.2"/>'
            .'</worksheet>';

        $files = [
            '[Content_Types].xml' => self::contentTypes(),
            '_rels/.rels' => self::rootRelationships(),
            'docProps/app.xml' => self::appProperties(),
            'docProps/core.xml' => self::coreProperties($title),
            'xl/workbook.xml' => self::workbook(),
            'xl/_rels/workbook.xml.rels' => self::workbookRelationships(),
            'xl/styles.xml' => self::styles(),
            'xl/worksheets/sheet1.xml' => $worksheet,
        ];

        return self::zip($files);
    }

    private static function displayValue(mixed $value): string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return (string) ($value ?? '');
    }

    private static function row(int $number, array $cells, ?int $height = null): string
    {
        $heightAttributes = $height ? ' ht="'.$height.'" customHeight="1"' : '';

        return '<row r="'.$number.'"'.$heightAttributes.'>'.implode('', $cells).'</row>';
    }

    private static function stringCell(string $coordinate, string $value, int $style): string
    {
        $value = preg_replace('/[^\x09\x0A\x0D\x20-\x{D7FF}\x{E000}-\x{FFFD}]/u', '', $value) ?? '';
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');

        return '<c r="'.$coordinate.'" s="'.$style.'" t="inlineStr"><is><t xml:space="preserve">'.$value.'</t></is></c>';
    }

    private static function numberCell(string $coordinate, int|float $value, int $style): string
    {
        return '<c r="'.$coordinate.'" s="'.$style.'"><v>'.$value.'</v></c>';
    }

    private static function columnName(int $number): string
    {
        $name = '';
        while ($number > 0) {
            $number--;
            $name = chr(65 + ($number % 26)).$name;
            $number = intdiv($number, 26);
        }

        return $name;
    }

    /** @param array<string, string> $files */
    private static function zip(array $files): string
    {
        $body = '';
        $directory = '';
        $offset = 0;
        $now = getdate();
        $dosTime = (($now['hours'] & 0x1F) << 11) | (($now['minutes'] & 0x3F) << 5) | (($now['seconds'] >> 1) & 0x1F);
        $dosDate = ((max(1980, $now['year']) - 1980) << 9) | (($now['mon'] & 0x0F) << 5) | ($now['mday'] & 0x1F);

        foreach ($files as $name => $contents) {
            $compressed = gzdeflate($contents, 6);
            $crc = crc32($contents);
            $nameLength = strlen($name);
            $compressedLength = strlen($compressed);
            $originalLength = strlen($contents);

            $local = pack(
                'VvvvvvVVVvv',
                0x04034B50,
                20,
                0,
                8,
                $dosTime,
                $dosDate,
                $crc,
                $compressedLength,
                $originalLength,
                $nameLength,
                0
            ).$name.$compressed;

            $directory .= pack(
                'VvvvvvvVVVvvvvvVV',
                0x02014B50,
                20,
                20,
                0,
                8,
                $dosTime,
                $dosDate,
                $crc,
                $compressedLength,
                $originalLength,
                $nameLength,
                0,
                0,
                0,
                0,
                0,
                $offset
            ).$name;

            $body .= $local;
            $offset += strlen($local);
        }

        return $body.$directory.pack(
            'VvvvvVVv',
            0x06054B50,
            0,
            0,
            count($files),
            count($files),
            strlen($directory),
            strlen($body),
            0
        );
    }

    private static function contentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>'
            .'<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>'
            .'</Types>';
    }

    private static function rootRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>'
            .'<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>'
            .'</Relationships>';
    }

    private static function workbook(string $sheetName = 'Request Report'): string
    {
        $sheetName = htmlspecialchars($sheetName, ENT_QUOTES | ENT_XML1, 'UTF-8');

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="'.$sheetName.'" sheetId="1" r:id="rId1"/></sheets></workbook>';
    }

    private static function workbookRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            .'</Relationships>';
    }

    private static function styles(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<fonts count="4">'
            .'<font><sz val="11"/><color theme="1"/><name val="Calibri"/><family val="2"/></font>'
            .'<font><b/><sz val="16"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            .'<font><i/><sz val="10"/><color rgb="FF647A9A"/><name val="Calibri"/></font>'
            .'<font><b/><sz val="10"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            .'</fonts>'
            .'<fills count="5"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FF061A2A"/><bgColor indexed="64"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFEAF1FA"/><bgColor indexed="64"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FF2463FF"/><bgColor indexed="64"/></patternFill></fill></fills>'
            .'<borders count="2"><border><left/><right/><top/><bottom/><diagonal/></border>'
            .'<border><left style="thin"><color rgb="FFDCE5F0"/></left><right style="thin"><color rgb="FFDCE5F0"/></right><top style="thin"><color rgb="FFDCE5F0"/></top><bottom style="thin"><color rgb="FFDCE5F0"/></bottom><diagonal/></border></borders>'
            .'<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            .'<cellXfs count="8">'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyAlignment="1"><alignment vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="0" fillId="3" borderId="1" xfId="0" applyFont="1"><alignment horizontal="center"/></xf>'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyFont="1"><alignment horizontal="center"/></xf>'
            .'<xf numFmtId="0" fontId="3" fillId="4" borderId="1" xfId="0" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyAlignment="1"><alignment vertical="top" wrapText="1"/></xf>'
            .'<xf numFmtId="49" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyAlignment="1"><alignment vertical="top" wrapText="1"/></xf>'
            .'</cellXfs><cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            .'</styleSheet>';
    }

    private static function appProperties(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">'
            .'<Application>Gateway PMS</Application></Properties>';
    }

    private static function coreProperties(string $title): string
    {
        $title = htmlspecialchars($title, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $created = now()->utc()->format('Y-m-d\TH:i:s\Z');

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
            .'<dc:title>'.$title.'</dc:title><dc:creator>Gateway PMS</dc:creator>'
            .'<dcterms:created xsi:type="dcterms:W3CDTF">'.$created.'</dcterms:created></cp:coreProperties>';
    }
}
