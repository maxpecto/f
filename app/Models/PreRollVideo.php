<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreRollVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'video_url',
        'target_url',
        'skippable_after_seconds',
        'is_active',
    ];
}
