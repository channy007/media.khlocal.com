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
        'cut_off_side',
        'tags',
        'error',
        'custom_crop',
        'created_by_id',
        'updated_by_id'
    ];

    public function project(){
        return $this->belongsTo(MediaProject::class,'project_id','id');
    }

    public function channel_source(){
        return $this->belongsTo(ChannelSource::class,'channel_source_id','id');
    }
}
