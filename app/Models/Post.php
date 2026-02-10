<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];
    protected $casts  = [
         'published_at' => 'datetime',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * Auto-generate slug dari judul saat menyimpan
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->slug = Str::slug($post->judul) . '-' . Str::random(5);
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope untuk mengambil berita terbaru di landing page
     */
    public function scopeLatestPublished($query)
    {
        return $query->where('is_published', true)->latest();
    }
}
