<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class YoutubeService
{

    public static function getVideoDetails($url){
    
        // Use parse_url() function to parse the URL
        // and return an associative array which
        // contains its various components
        $url_components = parse_url($url);
        
        // Use parse_str() function to parse the
        // string passed via URL
        parse_str($url_components['query'], $params);

        $youtubeLink = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=".$params['v'];
        $timeOut = 20;
        $response = Http::asJson()->withHeaders(
            [
                "X-goog-api-key" => "AIzaSyA11Z621PzZJc2Ff8HqKUY2xAs9n5CoHwQ"
            ]
        )->timeout($timeOut)->get($youtubeLink);

        return json_decode($response->body());
    }


}