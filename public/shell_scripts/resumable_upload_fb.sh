#!/bin/bash
#usage facebook_page_id(1) access_token(2) source(3)(full path source file) title(4) description(5)  thumbnail(6) 

##### capture arguments
facebook_link="https://graph.facebook.com/v15.0"
facebook_page_id="$1"
access_token="$2"
title="title=$4"
description="description=$5"
thumb="$6"
if [[ -z "$thumb" ]];
then
    echo "video thumbnail has been not set"
    thumb="test=''"
else
    echo "video thumbnail has been set"
    thumb="thumb=@$6"
fi

file="$3"
filename="${file%.*}"
file_extension="${file##*.}"
filesize=$(find "$filename.$file_extension" -printf "%s")
printf "File size = %d\n" $filesize
###### end capture argurment

##### static arguments

# access_token="EAASNg0JLXNgBAMbYUFpmvaFakgRzJSJ07iZBF1PN1IL9ISM3kpa2yV7W9gZAPAjLm4SjRLWg74xbZBUXMs0NGoS49Gz9MFFQKVjfpShXlqMWw1mm5WPDIkmqQZCIj5QM3TgXpCAyNxsZCyZCgpBNq0nZBnc6bmm7zVfTAF2zw2PTcbrneAak2dw"
# facebook_page_id="102318056026253"

##### end static argument

split_video(){
    #create folder for split file
    mkdir -p $filename

    #split file
    split -b 25485760 "$filename.$file_extension" "$filename/"
}

remove_spit_video(){
    rm -rf $filename
}

init_facebook_seasion(){
    output=$(curl -X POST \
    "$facebook_link/$facebook_page_id/videos" \
    -F "upload_phase=start" \
    -F "access_token=$access_token" \
    -F "file_size=$filesize" 2>/dev/null)

    output=$(echo $output | jq )

    echo "response json: $output"
    ##
    # Capture the response into variable
    ##
    video_id=$(echo $output | jq .video_id)
    start_offset=$(echo $output | jq .start_offset)
    end_offset=$(echo $output | jq .end_offset)
    upload_session_id=$(echo $output | jq .upload_session_id)

    echo "
    Upload result: 
    video_id: $video_id,
    start_offset: $start_offset,
    end_offset: $end_offset,
    upload_session_id: $upload_session_id
    "
}

upload_chunk_to_facebook(){
    echo "===== starting upload chunk with session $upload_session_id offset $start_offset====="
    video_chuck="$1"
    upload_response=$(curl -X POST \
    "$facebook_link/$facebook_page_id/videos"  \
    -F "upload_phase=transfer" \
    -F "upload_session_id=$upload_session_id" \
    -F "access_token=$access_token" \
    -F "start_offset=$start_offset" \
    -F "video_file_chunk=@$video_chuck")

    ##
    # Capture the response into variable
    ##
    start_offset=$(echo $output | jq .start_offset)

    echo "===== end upload chunk with session $upload_session_id offset $start_offset ====="

}

upload_end_session(){
    echo "===== start upload end session with session $upload_session_id ====="

    curl -X POST \
    "https://graph-video.facebook.com/v15.0/$facebook_page_id/videos"  \
    -F "upload_phase=finish" \
    -F "access_token=$access_token" \
    -F "upload_session_id=$upload_session_id" \
    -F "$description" \
    -F "$title" \
    -F "$thumb"

    echo "===== end upload end session with session $upload_session_id ====="
}

split_video

init_facebook_seasion

## start upload all video's chunks to facebook
for file_path in "$filename/*";
    do 
        upload_chunk_to_facebook $file_path
    done

## tell facebook the end of upload session
upload_end_session

## remove split video from storage
remove_spit_video
