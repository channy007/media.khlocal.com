<?php

namespace App\Utils\enums;

class MediaSourceStatus
{

    const NEW = 'new';
    const DOWNLOADED = 'downloaded';
    const CUTTED = 'cutted';
    const UPLOADED = 'uploaded';

    const CUTTING = 'cutting';
    const UPLOADING = 'uploading';
    const DOWNLOADING = 'downloading';

    const CUT_ERROR = 'cut_error';
    const UPLOAD_ERROR = 'upload_error';
    const DOWNLOAD_ERROR = 'download_error';


    public static function getAllStatus()
    {
        return [
            'new',
            'downloading',
            'downloaded',
            'download_error',
            'cutting',
            'cutted',
            'cut_error',
            'uploading',
            'uploaded',
            'upload_error'
        ];
    }
}
