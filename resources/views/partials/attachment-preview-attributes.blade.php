data-attachment-open
data-attachment-url="{{ route('attachments.show', $attachment) }}"
data-attachment-download="{{ route('attachments.show', ['attachment' => $attachment, 'download' => 1]) }}"
data-attachment-name="{{ $attachment->original_name }}"
data-attachment-kind="{{ $attachment->isImage() ? 'image' : ($attachment->isPdf() ? 'pdf' : 'file') }}"
aria-haspopup="dialog"
