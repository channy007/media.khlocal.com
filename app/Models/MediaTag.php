<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaTag extends Model
{
    use HasFactory;

    public $fillable = [
        'tag_id',
        'tag_name',
        'tag_description',
        'tag_channel'
    ];
}   
