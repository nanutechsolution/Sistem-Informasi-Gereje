<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sermon extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    public function uniqueIds() { return ['uuid']; }
    public function getRouteKeyName() { return 'uuid'; }

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Helper: Ambil Gambar Thumbnail YouTube Kualitas Tinggi
    public function getThumbnailUrlAttribute()
    {
        return "https://img.youtube.com/vi/{$this->youtube_id}/maxresdefault.jpg";
    }

    // Helper: Link Embed untuk Iframe
    public function getEmbedUrlAttribute()
    {
        return "https://www.youtube.com/embed/{$this->youtube_id}";
    }
}