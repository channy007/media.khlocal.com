<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'media_project_id'
    ];

    public function media_project(){
        return $this->belongsTo(MediaProject::class,'media_project_id','id');
    }
}
