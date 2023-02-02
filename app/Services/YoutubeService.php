<?php

namespace App\Services;

use App\Models\ChannelSource;
use App\Models\MediaSource;
use App\Utils\Enums\MediaSourceStatus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YoutubeService
{
    const API_KEY = "AIzaSyCVrNioJIunahYWbxuLs6oIneLAd_ttFrI"; //"AIzaSyA11Z621PzZJc2Ff8HqKUY2xAs9n5CoHwQ";

    public static function getVideoDetails($url)
    {

        // Use parse_url() function to parse the URL
        // and return an associative array which
        // contains its various components
        $url_components = parse_url($url);

        // Use parse_str() function to parse the
        // string passed via URL
        parse_str($url_components['query'], $params);

        $youtubeLink = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=" . $params['v'];
        $timeOut = 20;
        $response = Http::asJson()->withHeaders(
            [
                "X-goog-api-key" => self::API_KEY
            ]
        )->timeout($timeOut)->get($youtubeLink);

        return json_decode($response->body());
    }

    public static function autoDownload()
    {
        $channels = ChannelSource::all();

        foreach ($channels as $channel) {
            $channelId = self::getChannelId($channel);
            Log::info("=== channel ID: " . $channelId);
            if ($channelId) {
                $listVideo = self::getChannelVideo($channelId);
                Log::info("=== channel video: " . json_encode($listVideo));
                if(!isset($listVideo->items))
                    continue;
                self::insertMediaSource($channel,$listVideo->items);
            }

            break;
        }
    }

    private static function insertMediaSource($channel, $youtubeVideos)
    {
        foreach ($youtubeVideos as $video) {
            $videoSnipet = $video->snippet;
            if(!isset($videoSnipet))
                continue;
            Log::info("=== channel video: " . json_encode($videoSnipet));
            $videoUrl = "https://www.youtube.com/watch?v=" . $video->id->videoId;
            MediaSource::create(
                [
                    'channel_id' => $channel->id,
                    'source_name' => $videoSnipet->title,
                    'source_text' => $videoSnipet->description,
                    'source_url' => $videoUrl,
                    'status' => MediaSourceStatus::NEW
                ]
            );
        }
    }

    private static function getChannelVideo($channelId)
    {
        $query = [
            "channelId" => $channelId,
            "part" => "snippet,id",
            "order" => "date",
            "type" => "video",
            "maxResults" => 2
        ];
        $youtubeLink = "https://www.googleapis.com/youtube/v3/search";
        $timeOut = 20;
        $response = Http::asJson()->withHeaders(
            [
                "X-goog-api-key" => self::API_KEY
            ]
        )->timeout($timeOut)->get($youtubeLink, $query);

        return json_decode($response->body());
    }

    private function getChannelId($channel)
    {
        if (!$channel->channel_id) {
            $youtubeChannel = self::getChannel($channel);
            return optional($youtubeChannel)->id->channelId;
        }
        return $channel->channel_id;
    }

    /* 
    * ex: $url = "http://twitter.com/pwsdedtch";
    * return pwsdedtch
    */
    private static function getChannel($channel)
    {

        $channelUrl = "https://www.googleapis.com/youtube/v3/search";
        $query = [
            "part" => "id,snippet",
            "type" => "channel"
        ];
        $path = parse_url($channel->url, PHP_URL_PATH); // gives "/pwsdedtech"
        $channelId = substr($path, 1); // gives "pwsdedtech"\
        $query["q"] = str_replace("@", "", $channelId);
        $channelYoutube = self::makeRequest($channelUrl, $query);
        Log::info("============== channel: " . json_encode($channelYoutube));
        if (isset($channelYoutube->items)) {
            return $channelYoutube->items[0];
        }

        return null;
    }

    private function makeRequest($url, $query)
    {
        $timeOut = 20;
        $response = Http::asJson()->withHeaders(
            [
                "X-goog-api-key" => self::API_KEY
            ]
        )->timeout($timeOut)->get($url, $query);

        return json_decode($response->body());
    }
}
