<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel',
        'channel_id',
        'name',
        'url',
        'created_by_id',
        'updated_by_id',
        'custom_crop',
        'description',
        'country',
        'segment_cut'
    ];

    public function media_projects(){
        return $this->hasMany(ProjectChannelSource::class,'channel_source_id','id');
    }

    public function media_project(){
        return $this->hasOne(ProjectChannelSource::class,'channel_source_id','id');
    }

}
