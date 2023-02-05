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
        $channels = ChannelSource::with('media_project.project')->get();

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
            $mediaProject = optional($channel->media_project)->project;

            if(MediaSource::whereSourceVid($video->id->videoId)->exists()){
                continue;
            }
            MediaSource::create(
                [
                    'project_id' => optional($mediaProject)->id,
                    'created_by_id' => 1,
                    'channel_id' => $channel->id,
                    'source_name' => $videoSnipet->title,
                    'source_text' => $videoSnipet->description,
                    'source_url' => $videoUrl,
                    'status' => MediaSourceStatus::NEW,
                    'custom_crop' => $channel->custom_crop,
                    'segment_cut' => $channel->segment_cut,
                    'tags' => optional($mediaProject)->tags,
                    'source_channel' => $videoSnipet->channelTitle,
                    'source_vid' => $video->id->videoId,
                    'channel_source_id' => $channel->id
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
    public static function getChannel($channelUrl)
    {
        $channelYoutubeAPI = "https://www.googleapis.com/youtube/v3/search";
        $query = [
            "part" => "id,snippet",
            "type" => "channel"
        ];
        $path = parse_url($channelUrl, PHP_URL_PATH); // gives "/pwsdedtech"
        $channelId = substr($path, 1); // gives "pwsdedtech"\
        $query["q"] = $channelId;
        $channelYoutube = self::makeRequest($channelYoutubeAPI, $query);
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
