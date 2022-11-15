source ./youtube_download.sh
# source ./facebook_upload.sh
source ./ffmpeg_cut.sh

file_downloaded="$(youtube_download $1)"

file_cutted="$(cut_video $file_downloaded "fade")"

