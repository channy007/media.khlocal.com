<?php

namespace App\Services;

use App\Models\FileStorage;
use App\Utils\enums\FileExtension;
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



}