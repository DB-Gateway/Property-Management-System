<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\RequestAttachment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentPreviewTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private PropertyRequest $propertyRequest;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake();
        $dealer = Dealer::create(['source_no' => 1, 'name' => 'Preview Dealer', 'area' => 'Metro Manila', 'brand' => 'Test']);
        $this->owner = User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]);
        $this->propertyRequest = PropertyRequest::create([
            'reference_no' => 'PREVIEW-001', 'dealer_id' => $dealer->id,
            'submitted_by' => $this->owner->id, 'submitter_name' => $this->owner->name,
            'designation' => 'Dealer Representative', 'branch' => $dealer->name,
            'area' => $dealer->area, 'request_type' => 'Electrical Works',
            'priority' => 'regular', 'description' => 'Preview test',
            'request_date' => today(), 'due_date' => today()->addDays(7), 'status' => 'pending',
        ]);
    }

    private function attachment(string $name, string $mime): RequestAttachment
    {
        $path = 'request-attachments/'.$name;
        Storage::put($path, 'test file contents');

        return $this->propertyRequest->attachments()->create([
            'category' => 'request', 'path' => $path, 'original_name' => $name,
            'mime_type' => $mime, 'size' => 18,
        ]);
    }

    public function test_images_and_pdfs_are_inline_and_can_be_explicitly_downloaded(): void
    {
        foreach (['photo.png' => 'image/png', 'report.pdf' => 'application/pdf', 'résumé.pdf' => 'application/octet-stream'] as $name => $mime) {
            $attachment = $this->attachment($name, $mime);
            $response = $this->actingAs($this->owner)->get(route('attachments.show', $attachment));
            $response->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
            $response->assertHeader('Content-Type', $attachment->isPdf() ? 'application/pdf' : $mime);
            $this->assertStringStartsWith('inline;', $response->headers->get('Content-Disposition'));
            $download = $this->get(route('attachments.show', ['attachment' => $attachment, 'download' => 1]));
            $download->assertOk();
            $this->assertStringStartsWith('attachment;', $download->headers->get('Content-Disposition'));
        }
    }

    public function test_office_files_remain_downloads_and_support_availability_checks(): void
    {
        $attachment = $this->attachment('report.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $response = $this->actingAs($this->owner)->get(route('attachments.show', $attachment));
        $response->assertOk();
        $this->assertStringStartsWith('attachment;', $response->headers->get('Content-Disposition'));
        $this->head(route('attachments.show', $attachment))->assertOk();
    }

    public function test_missing_attachments_return_404(): void
    {
        $attachment = $this->attachment('missing.png', 'image/png');
        Storage::delete($attachment->path);
        $this->actingAs($this->owner)->get(route('attachments.show', $attachment))->assertNotFound();
    }

    public function test_other_dealers_cannot_preview_or_download_attachments(): void
    {
        $attachment = $this->attachment('private.pdf', 'application/pdf');
        $other = User::factory()->create(['role' => 'dealer', 'dealer_id' => null]);
        $this->actingAs($other)->get(route('attachments.show', $attachment))->assertForbidden();
        $this->get(route('attachments.show', ['attachment' => $attachment, 'download' => 1]))->assertForbidden();
    }

    public function test_request_files_render_modal_triggers_with_image_error_hooks(): void
    {
        $attachment = $this->attachment('photo.png', 'image/png');
        $this->actingAs($this->owner)->get(route('requests.show', $this->propertyRequest))
            ->assertOk()->assertSee('id="attachmentPreviewDialog"', false)
            ->assertSee('data-attachment-open', false)->assertSee('data-attachment-image', false)
            ->assertSee('data-attachment-kind="image"', false);

        $html = view('requests.partials.files', ['files' => collect([$attachment]), 'gallery' => true])->render();
        $this->assertStringNotContainsString('target="_blank"', $html);
    }
}
