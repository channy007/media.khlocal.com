<?php

namespace App\Services;

use App\Utils\Generics\ResponseDTO;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ApplicationService
{

    public function updateToken($application){

        $result = new ResponseDTO([]);
        if(!$application->user_id){
            $result->error = "User ID on Application is null!";
            return $result;
        }

        $result = FacebookTokenService::generateLongLifeUserToken($application->short_access_token,$application,$result);
        if($result->hasError())
            return $result;
        $longLifeUserTokenResult = $result->data;
        $longLifePageTokensResult = FacebookTokenService::generateLongLifePageToken($application,$longLifeUserTokenResult->access_token);

        if(isset($longLifePageTokensResult->error)){
            $result->error = "error generate long life page access token!";
            return $result;
        }
        $createdTokenAt = Carbon::now();
        foreach($longLifePageTokensResult->data as $page){
            DB::table('media_projects')
            ->where("application_id",$application->id)
            ->where("page_id",$page->id)
            ->update(
                [
                    "long_user_access_token" => $longLifeUserTokenResult->access_token,
                    "created_token_at" => $createdTokenAt,
                    "long_user_access_token_expire_at" => $createdTokenAt->addDays(59),//$createdTokenAt->addDays((int)($longLifeUserTokenResult->expires_in / 86400)),
                    "long_page_access_token" => $page->access_token
                ]
            );
        }

        return $result;

    }

}