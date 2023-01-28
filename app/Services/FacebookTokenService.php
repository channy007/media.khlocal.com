<?php

namespace App\Services;

use App\Models\Application;
use App\Utils\Generics\ResponseDTO;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookTokenService
{
    const FB_ACCESS_TOKEN_URL = 'https://graph.facebook.com/v15.0/oauth/access_token';

    public static function generateLongLifeUserToken($mediaProject,Application $application,ResponseDTO $result){
        if (!isset($mediaProject->short_user_access_token)) {
            $result->error = "Media project access token required!";
            return $result;
        }
        
        try {
            Log::info("============ starting generat long life token ============");

            $params = [
                'grant_type' => 'fb_exchange_token',
                'client_id' => $application->app_id,
                'client_secret' => $application->client_secret,
                'fb_exchange_token' => $mediaProject->short_user_access_token
            ];

            $timeOut = 20;
            $facebookResponse = Http::asJson()->timeout($timeOut)->post(
                self::FB_ACCESS_TOKEN_URL . "?" . http_build_query($params)
            );

            if ($facebookResponse->successful()) {
                $result->data = json_decode($facebookResponse->body());
            }else {
                Log::info("============ generat long life token fails response: " . $facebookResponse->body());
                $result->error = "Facebook generate long token " . json_decode($facebookResponse->body())->error->message;
            }
        } catch (Exception $e) {
            Log::info("============ generat long life token error ============" . $e->getMessage());
            $result->error = $e->getMessage();
        } finally {
            return $result;
        }
    }

    public function update(){
        
    }
    

    public static function generateLongLifePageToken($application,$userLongLifeToken){

        $facebookUrl = "https://graph.facebook.com/v15.0/".$application->user_id."/accounts";
        $params = [
            'access_token' => $userLongLifeToken
        ];
        $timeOut = 20;
        $facebookResponse = Http::asJson()->timeout($timeOut)->get(
            $facebookUrl . "?" . http_build_query($params)
        );

        $facebookResult = json_decode($facebookResponse->body());

        return $facebookResult;
    }

}