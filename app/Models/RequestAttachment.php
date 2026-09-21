<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAttachment extends Model
{
    protected $fillable = ['property_request_id', 'category', 'path', 'original_name', 'mime_type', 'size', 'uploaded_by_id', 'uploaded_by_name', 'uploaded_by_role'];

    public function getUploaderLabelAttribute(): ?string
    {
        return $this->uploaded_by_role
            ? (User::ROLES[$this->uploaded_by_role] ?? $this->uploaded_by_role).($this->uploaded_by_name ? ' — '.$this->uploaded_by_name : '')
            : null;
    }

    public function propertyRequest()
    {
        return $this->belongsTo(PropertyRequest::class);
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isPdf(): bool
    {
        return str_contains($this->mime_type, 'pdf') || str_ends_with(strtolower($this->original_name), '.pdf');
    }

    public function isWord(): bool
    {
        $ext = strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION));
        return in_array($ext, ['doc', 'docx']) || str_contains($this->mime_type, 'word') || str_contains($this->mime_type, 'officedocument.wordprocessingml');
    }

    public function isExcel(): bool
    {
        $ext = strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION));
        return in_array($ext, ['xls', 'xlsx', 'csv']) || str_contains($this->mime_type, 'spreadsheet') || str_contains($this->mime_type, 'excel');
    }

    public function fileTypeLabel(): string
    {
        if ($this->isImage()) return 'PHOTO';
        if ($this->isPdf()) return 'PDF';
        if ($this->isWord()) return 'DOCX';
        if ($this->isExcel()) return 'XLSX';
        return 'FILE';
    }

    public function fileBadgeClass(): string
    {
        if ($this->isImage()) return 'badge-img';
        if ($this->isPdf()) return 'badge-pdf';
        if ($this->isWord()) return 'badge-word';
        if ($this->isExcel()) return 'badge-excel';
        return 'badge-generic';
    }

    public function fileIcon(): string
    {
        if ($this->isImage()) return '🖼';
        if ($this->isPdf()) return '📄';
        if ($this->isWord()) return '📝';
        if ($this->isExcel()) return '📊';
        return '📁';
    }
}
