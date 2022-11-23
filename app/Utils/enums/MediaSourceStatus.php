<?php

namespace App\Utils\enums;

class MediaSourceStatus
{

    const NEW = 'new';
    const DOWNLOADED = 'downloaded';
    const CUT = 'cutted';
    const UPLOADED = 'uploaded';

    const CUTTING = 'cutting';
    const UPLOADING = 'uploading';
    const DOWNLOADING = 'downloading';

    const CUT_ERROR = 'cut_error';
    const UPLOAD_ERROR = 'upload_error';
    const DOWNLOAD_ERROR = 'download_error';

}
