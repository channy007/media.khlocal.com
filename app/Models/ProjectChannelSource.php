<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectChannelSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'channel_source_id'
    ];

    public function channel_source(){
        return $this->belongsTo(ChannelSource::class,'channel_source_id','id');
    }
}
