<?php

namespace App\Services;

use App\Models\FileStorage;
use App\Sources\Generics\ResponseDTO;
use App\Utils\Enums\FileExtension;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileStorageService
{


    public static function createFileStorage($mediaSource)
    {
        $fileStorage = FileStorage::whereMediaSourceId($mediaSource->id)->first();
        if (!$fileStorage) {
            $fileStorage = new FileStorage();
        }
        $fileStorage->media_source_id = $mediaSource->id;
        $fileStorage->name = strtolower(Str::random(45));
        $fileStorage->name_cutted = $fileStorage->name . '_cut';
        $fileStorage->extension = FileExtension::MP4;
        $fileStorage->path = public_path('storage') . '/videos';
        $fileStorage->save();
        return $fileStorage;
    }

    public static function createFileStorageByFilePath($mediaSource,$fullFilePath){

        if (!file_exists($fullFilePath)) {
            return new ResponseDTO(['message' => 'Finding file by '.$fullFilePath.' not found!']);
        }

        Log::info("================= path: ".dirname($fullFilePath));
        Log::info("================= filename: ".basename($fullFilePath,'.mp4'));
        
        $fileStorage = FileStorage::whereMediaSourceId($mediaSource->id)->first();
        if (!$fileStorage) {
            $fileStorage = new FileStorage();
        }

        $fileStorage->media_source_id = $mediaSource->id;
        $fileStorage->name = basename($fullFilePath,'.mp4');
        $fileStorage->name_cutted = $fileStorage->name . '_cut';
        $fileStorage->extension = FileExtension::MP4;
        $fileStorage->path = dirname($fullFilePath);
        $fileStorage->save();
        return $fileStorage;
    }


}