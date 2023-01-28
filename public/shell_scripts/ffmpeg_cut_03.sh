#!/bin/bash
#usage: ./ffmpeg.sh filename(1) transition(2) seg_start(3) seg_length(4) seg_gap(5) flip(6) resolution(7) project-name(8) cut_off(9) cut_off_side(10) custom_crop(11)

#set -vx
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
cutoff="$9"
cutoff_side="${10}"
custom_crop="${11}"
start_time=$(/bin/date +%s)
##### 
if [[ -z "$flip" ]];
then
    flip=""
else
    flip=",$flip"
fi
#####################33
if [[ "$resolution" == "1:1" ]]; then
        scale="1080:1080"
elif [[ "$resolution" == "4:3" ]]; then
        scale="640:480"
elif [[ "$resolution" == "16:9" ]]; then
        scale="1280:720"
fi
if [[ -z "$custom_crop" ]]; then
	width=$(ffprobe -v error -show_entries stream=width -of default=noprint_wrappers=1:nokey=1 "${file}")
	height=$(ffprobe -v error -show_entries stream=height -of default=noprint_wrappers=1:nokey=1 "${file}")
	IFS=":" read -a resolution <<< $resolution
	if [[ $width -gt $height ]]; then
        	if [[ $width -gt $(( ($height-$height*$cutoff/10)*${resolution[0]}/${resolution[1]} )) ]]; then
                	w=$(( ($height-$height*$cutoff/10)*${resolution[0]}/${resolution[1]} ))
             		h=$(( $height-$height*$cutoff/10 ))
        	else
                	h=$(( $width*${resolution[1]}/${resolution[0]} ))
        	fi
	else
        	if [[ $height -gt $(( ($width-$width*$cutoff/10)*${resolution[1]}/${resolution[0]} )) ]]; then
                	h=$(( ($width-$width*$cutoff/10)*${resolution[1]}/${resolution[0]} ))
                	w=$(( $width-$width*$cutoff/10 ))
        	else
                	w=$(( $height*${resolution[1]}/${resolution[0]} ))
        	fi
	fi
	if [[ $cutoff_side -eq 2 ]]; then
        	video_crop="crop=${w}:${h}:0:0"
	elif [[ $cutoff_side -eq 1 ]]; then
        	video_crop="crop=${w}:${h}:$(($width-$w)):$(($height-$h))"
	else
        	video_crop="crop=${w}:${h}"
	fi
else
	video_crop="crop=${custom_crop}"
fi
video_crop="$video_crop,scale=${scale}"
#################33
echo $video_crop
ffmpeg -i $file -vf "$video_crop$flip" -movflags +faststart -c:v libx264 -profile:v main -b:v 712k -r:v 30 -preset veryfast -c:a copy -y "${file_name}_crop.${extension}" > /dev/null 2>&1
end_time=$(/bin/date +%s)
elapsed=$((end_time - start_time))
eval "echo Crop elapsed time: $(date -ud "@$elapsed" +'$((%s/3600/24)) days %H hr %M min %S sec')"
#exit 0

##################################################################################################################
min_length=$(bc -l <<< "($video_length/60-5)*0.75+5")
min_length=${min_length%.*}
seg_length=$(shuf -i $min_length-$length -n 1)
seg_gap=$seg_start
seg_end=$(( $seg_start+$seg_length ))
fade_duration=1
fade_prev=0
fade_next=1
all_duration=0
while (($(echo "$video_length >= $seg_end" | bc))); do
        all_video_fade="$all_video_fade$video_fade"
        all_audio_fade="$all_audio_fade$audio_fade"

	speed=$(bc -l <<< "1 - $(( $RANDOM%5+5 )) / 100")
	#speed=1
        all_duration=$(bc -l <<< "$all_duration + $seg_length * $speed")
        offset=$(bc -l <<< "$all_duration - $fade_duration * $fade_next")
	
	video_scale="$video_scale[0:v]trim=start=$seg_start:end=$seg_end,setpts=$speed*(PTS-STARTPTS)[v$fade_prev];"
	#video_scale="$video_scale[0:v]trim=start=$seg_start:end=$seg_end[v$fade_prev];"
        video_fade="[vfade$fade_prev][v$fade_next]xfade=transition=$transition:duration=$fade_duration:offset=$offset[vfade$fade_next];"

	audio_scale="$audio_scale[0:a]atrim=start=$seg_start:end=$seg_end,asetpts=PTS-STARTPTS,atempo=1/$speed[a$fade_prev];"
	#audio_scale="$audio_scale[0:a]atrim=start=$seg_start:end=$seg_end[a$fade_prev];"
        audio_fade="[afade$fade_prev][a$fade_next]acrossfade=d=$fade_duration[afade$fade_next];"

	seg_length=$(shuf -i $min_length-$length -n 1)
	seg_gap=$(shuf -i 5-$gap -n 1)
        seg_start=$(( $seg_end+$seg_gap ))
        seg_end=$(( $seg_start+$seg_length ))
        (( fade_prev++ ))
        (( fade_next++ ))
done
fade_prev=$(( fade_prev - 1 ))
all_video_fade="[v0]copy[vfade0];$all_video_fade[vfade$fade_prev]format=yuv420p"
all_audio_fade="[a0]acopy[afade0];$all_audio_fade[afade$fade_prev]acopy"
set -vx
ffmpeg -y -hide_banner -i "${file_name}_crop.${extension}" \
        -filter_complex "$video_scale$all_video_fade;$audio_scale$all_audio_fade" \
        -metadata brand="mp42" \
        -metadata creation_time="$(date -u +%FT%T.%NZ)" \
        -metadata:s:v:0 handler_name="ISO Media file produced by $project_name Project. Created on : $(date -u +%m/%d/%Y)." \
        -metadata:s:a:0 handler_name="ISO Media file produced by $project_name Project. Created on : $(date -u +%m/%d/%Y)." \
        -profile:v main -b:v 712k -r:v 30 -preset veryfast -movflags +faststart \
        "${file_name}_cut.${extension}" > /dev/null 2>&1
##################################################################################################################
end_time=$(/bin/date +%s)
elapsed=$((end_time - start_time))
eval "echo Cut elapsed time: $(date -ud "@$elapsed" +'$((%s/3600/24)) days %H hr %M min %S sec')"
