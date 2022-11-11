<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaSource extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'source_name',
        'source_url',
        'source_from',
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
        'flip',
        'status'
    ];

    public function project(){
        return $this->belongsTo(MediaProject::class,'project_id','id');
    }
}
