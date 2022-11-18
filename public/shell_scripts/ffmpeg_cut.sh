#!/bin/bash
#usage: ./ffmpeg.sh filename transition seg_start seg_length seg_gap fliph flipv
#new usage: ./ffmpeg.sh filename transition seg_start seg_length seg_gap flip


file="$1"
transition="$2"
file_name="${file%.*}"
extension="${file##*.}"
video_length=$(ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 "${file}")
seg_start="$3"
length="$4"
gap="$5"
flip="$6"
resolution="$7"
project_name="$8"
scale=""

if [[ -z "$flip" ]];
then
    echo "flip is not set"
    flip=""
else
    echo "flip has been set"
    flip=",$flip"
fi

## finding scal base on resolution
if [[ "$resolution" -eq "1:1" ]];
then
        scale="crop=ih:ih,scale=1080:1080"
elif [[ "$resolution" -eq "4:3" ]];
then
        scale="crop=ih*4/3:ih,scale=640:480" 
elif [[ "$resolution" -eq "16:9" ]];
then
        scale="crop=ih*16/9:ih,scale=1280:720"
else
        scale="crop=ih*4/3:ih,scale=640:480" 
fi

echo "resolution is $resolution, scal $scal"



##################################################################################################################
rm -rf "${file_name}"
mkdir "${file_name}"
clear -x
##################################################################################################################
start_time=$(/bin/date +%s)
##################################################################################################################
seg_gap=$seg_start
seg_length=$(( $RANDOM%$length+1 ))
seg_end=$(( $seg_start+$seg_length ))
fade_duration=1
fade_prev=0
fade_next=1
all_duration=0
while (($(echo "$video_length >= $seg_end" | bc))); do
        all_video_fade="$all_video_fade$video_fade"
        all_audio_fade="$all_audio_fade$audio_fade"
        inputs="$inputs -i $file "

	speed=$(bc -l <<< "1 - $(( $RANDOM%5+5 )) / 100")
        all_duration=$(bc -l <<< "$all_duration + $seg_length * $speed")
        offset=$(bc -l <<< "$all_duration - $fade_duration * $fade_next")
	
	video_scale="$video_scale[$fade_prev:v]trim=start=$seg_start:end=$seg_end,$scale,setpts=$speed*(PTS-STARTPTS)$flip[v$fade_prev];"
        video_fade="[vfade$fade_prev][v$fade_next]xfade=transition=$transition:duration=$fade_duration:offset=$offset[vfade$fade_next];"

	audio_scale="$audio_scale[$fade_prev:a]atrim=start=$seg_start:end=$seg_end,asetpts=PTS-STARTPTS,atempo=1/$speed[a$fade_prev];"
        audio_fade="[afade$fade_prev][a$fade_next]acrossfade=d=$fade_duration[afade$fade_next];"

	seg_length=$(( $RANDOM%$length+1 ))
	seg_gap=$(( $RANDOM%$gap+1 ))
        seg_start=$(( $seg_end+$seg_gap ))
        seg_end=$(( $seg_start+$seg_length ))
        (( fade_prev++ ))
        (( fade_next++ ))
done
fade_prev=$(( fade_prev - 1 ))
all_video_fade="[v0]copy[vfade0];$all_video_fade[vfade$fade_prev]format=yuv420p"
all_audio_fade="[a0]acopy[afade0];$all_audio_fade[afade$fade_prev]acopy"
set -vx
ffmpeg -y -hide_banner $inputs \
        -filter_complex "$video_scale$all_video_fade;$audio_scale$all_audio_fade" \
        -metadata brand="mp42" \
        -metadata creation_time="$(date -u +%FT%T.%NZ)" \
        -metadata:s:v:0 handler_name="ISO Media file produced by $project_name Project. Created on : $(date -u +%m/%d/%Y)." \
        -metadata:s:a:0 handler_name="ISO Media file produced by $project_name Project. Created on : $(date -u +%m/%d/%Y)." \
        -movflags +faststart \
        "${file_name}_cut.${extension}" > /dev/null 2>&1
##################################################################################################################
end_time=$(/bin/date +%s)
elapsed=$((end_time - start_time))
eval "echo Elapsed time: $(date -ud "@$elapsed" +'$((%s/3600/24)) days %H hr %M min %S sec')"
rm -rf "${file_name}"