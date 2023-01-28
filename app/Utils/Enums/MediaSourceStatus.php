<?php

namespace App\Utils\Enums;

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

    const PENDING_DOWNLOAD = 'pending_download';
    const PENDING_CUT = 'pending_cut';
    const PENDING_UPLOAD = 'pending_upload';



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
