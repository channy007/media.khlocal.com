<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaProject extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'channel',
        'resolution',
        'app_id',
        'page_id',
        'short_user_access_token',
        'long_user_access_token_expire_at',
        'long_page_access_token',
        'created_token_at',
        'status',
        'client_secret',
        'application_id',
        'tags'
        
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }

    public function channel_sources(){
        return $this->hasMany(ProjectChannelSource::class,'project_id','id');
    }
}
