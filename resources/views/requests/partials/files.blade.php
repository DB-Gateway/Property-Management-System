@forelse($files as $attachment)
    <a class="workflow-file {{ ($gallery ?? false) ? 'workflow-file-tile' : '' }}" href="{{ route('attachments.show', $attachment) }}" target="_blank" rel="noopener">
        @if(($gallery ?? false) && $attachment->isImage())
            <img src="{{ route('attachments.show', $attachment) }}" alt="{{ $attachment->original_name }}">
        @else
            <span class="workflow-file-icon {{ $attachment->isPdf() ? 'icon-pdf' : ($attachment->isWord() ? 'icon-word' : ($attachment->isExcel() ? 'icon-excel' : '')) }}">
                @if($attachment->isPdf())
                    PDF
                @elseif($attachment->isWord())
                    DOC
                @elseif($attachment->isExcel())
                    XLS
                @elseif($attachment->isImage())
                    IMG
                @else
                    ▤
                @endif
            </span>
        @endif
        <span class="workflow-file-copy">
            <strong>
                <span class="workflow-file-badge {{ $attachment->fileBadgeClass() }}">{{ $attachment->fileTypeLabel() }}</span>
                {{ $attachment->original_name }}
            </strong>
            <small>{{ number_format($attachment->size / 1024, 1) }} KB · Open</small>
        </span>
    </a>
@empty
    <div class="empty-state compact">{{ $emptyMessage ?? 'No files attached.' }}</div>
@endforelse
