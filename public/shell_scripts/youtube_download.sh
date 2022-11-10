#!/bin/bash
#usage video-link 

youtube_url="$1"

## get file name
filename=$(youtube-dl --get-title $youtube_url)
replace="-"
filename=${filename//[^[:alnum:]_-]/$replace}
filename+="_time_"
filename+=$(date +%s)
youtube-dl -o "$filename.%(ext)s" $youtube_url

return 1