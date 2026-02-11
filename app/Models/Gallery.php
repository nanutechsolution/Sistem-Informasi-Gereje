<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Gallery extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
