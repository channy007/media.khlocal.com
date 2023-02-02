<?php

namespace App\Http\Controllers\Youtube;

use App\Http\Controllers\Controller;
use App\Services\YoutubeService;
use App\Utils\Generics\ResponseDTO;
use Illuminate\Http\Request;

class YoutubeController extends Controller
{


    public function getVideoDetails(Request $request){

        $result = new ResponseDTO(
            [
                'data' => YoutubeService::getVideoDetails($request['url'])
            ]
            );
        return responseDto($result);
    }

    public function autoDonwload(Request $request){
        YoutubeService::autoDownload();

        $result = new ResponseDTO([]);
        return responseDto($result);
    }
}
