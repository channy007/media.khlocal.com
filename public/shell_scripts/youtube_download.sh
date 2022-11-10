#!/bin/bash
#usage video-link 

youtube_url="$1"
file_name="$2"

youtube-dl -o "$file_name" $youtube_url

# youtube-dl -o "/var/www/share/$filename.%(ext)s" $youtube_url

# ## get file name
# filename=$(youtube-dl --get-title $youtube_url)
# replace="-"
# filename=${filename//[^[:alnum:]_-]/$replace}
# filename+="_time_"
# filename+=$(date +%s)