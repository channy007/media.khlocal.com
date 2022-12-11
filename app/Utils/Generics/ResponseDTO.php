<?php

namespace App\Sources\Generics;

use App\Sources\Utils\Enums\StatusCode;
use App\Sources\Utils\Enums\StatusType;

class ResponseDTO
{
    public $data;
    public $message;
    public $error;
    public $total;
    public $statusCode;
    public $metaData;

    public function __construct($data)
    {

        $this->data = $data['data'] ?? null;
        $this->metaData = $data['metaData'] ?? null;
        $this->total = $data['total'] ?? null;
        $this->statusCode = $data['statusCode'] ?? StatusCode::OK;
        $this->message = $data['message'] ?? null;
        $this->error = $data['error'] ?? null;
    }

}
