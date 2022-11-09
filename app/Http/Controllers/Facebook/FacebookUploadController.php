<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class FacebookUploadController extends Controller
{
    public function download()
    {
        $youtubeDl = new YoutubeDl();
        $youtubeDl->setBinPath('C:\Program Files\Youtube-dl');
        $videos = $youtubeDl->download(
            Options::create()->downloadPath('C:\Users\USER\Documents')->url("https://www.youtube.com/watch?v=zBjJUV-lzHo")
        );
        $result = [];
        foreach ($videos->getVideos() as $video) {
            if ($video->getError() !== null) {
                $result[] = $video->getError();
            } else {
                $result[] = $video->getTitle();
            }
        }
        return response()->json(['data' => $result]);
    }
}
