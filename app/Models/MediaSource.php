<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'source_name',
        'channel_source_id',
        'source_url',
        'source_channel',
        'source_text',
        'transition',
        'resolution',
        'seg_start',
        'seg_length',
        'seg_gap',
        'segment',
        'flip_h',
        'flip_v',
        'status',
        'thumb',
        'flip',
        'cut_off',
        'tags',
        'path_downloaded',
        'path_cutted'
    ];

    public function project(){
        return $this->belongsTo(MediaProject::class,'project_id','id');
    }
}
