<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileStorage extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_source_id',
        'name',
        'name_cutted',
        'extension',
        'path',
        'path_cutted'
    ];
}
