<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    public function uniqueIds() { return ['uuid']; }
    public function getRouteKeyName() { return 'uuid'; }

    // Helper untuk icon berdasarkan ekstensi
    public function getFileIconAttribute()
    {
        $ext = pathinfo($this->file_path, PATHINFO_EXTENSION);
        return match(strtolower($ext)) {
            'pdf' => 'pdf',
            'doc', 'docx' => 'word',
            'xls', 'xlsx' => 'excel',
            default => 'file'
        };
    }

    public function getSizeAttribute()
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            $bytes = Storage::disk('public')->size($this->file_path);
            if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
            if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
            return $bytes . ' bytes';
        }
        return '0 KB';
    }
}