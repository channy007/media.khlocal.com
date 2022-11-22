<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel',
        'name',
        'app_id',
        'url',
        'created_by_id',
        'updated_by_id'
    ];


}
