ffmpeg -y -hide_banner 
-i video.mp4 
-i video.mp4 
-i video.mp4 
-i video.mp4 
-i video.mp4 
-i video.mp4 
-i video.mp4 
-i video.mp4 
-i video.mp4 
-i video.mp4 
-filter_complex '
[0:v]trim=start=5:end=58,crop=ih*4/3:ih,scale=640:480,setpts=.92000000000000000000*(PTS-STARTPTS),hflip[v0];
[1:v]trim=start=59:end=107,crop=ih*4/3:ih,scale=640:480,setpts=.93000000000000000000*(PTS-STARTPTS),hflip[v1];
[2:v]trim=start=109:end=199,crop=ih*4/3:ih,scale=640:480,setpts=.94000000000000000000*(PTS-STARTPTS),hflip[v2];
[3:v]trim=start=220:end=267,crop=ih*4/3:ih,scale=640:480,setpts=.94000000000000000000*(PTS-STARTPTS),hflip[v3];
[4:v]trim=start=273:end=355,crop=ih*4/3:ih,scale=640:480,setpts=.93000000000000000000*(PTS-STARTPTS),hflip[v4];
[5:v]trim=start=356:end=443,crop=ih*4/3:ih,scale=640:480,setpts=.92000000000000000000*(PTS-STARTPTS),hflip[v5];
[6:v]trim=start=452:end=499,crop=ih*4/3:ih,scale=640:480,setpts=.95000000000000000000*(PTS-STARTPTS),hflip[v6];
[7:v]trim=start=518:end=545,crop=ih*4/3:ih,scale=640:480,setpts=.91000000000000000000*(PTS-STARTPTS),hflip[v7];
[8:v]trim=start=561:end=611,crop=ih*4/3:ih,scale=640:480,setpts=.92000000000000000000*(PTS-STARTPTS),hflip[v8];
[9:v]trim=start=620:end=660,crop=ih*4/3:ih,scale=640:480,setpts=.93000000000000000000*(PTS-STARTPTS),hflip[v9];

[v0]copy[vfade0];
[vfade0][v1]xfade=transition=fade:duration=1:offset=47.76000000000000000000[vfade1];
[vfade1][v2]xfade=transition=fade:duration=1:offset=91.40000000000000000000[vfade2];
[vfade2][v3]xfade=transition=fade:duration=1:offset=175.00000000000000000000[vfade3];
[vfade3][v4]xfade=transition=fade:duration=1:offset=218.18000000000000000000[vfade4];
[vfade4][v5]xfade=transition=fade:duration=1:offset=293.44000000000000000000[vfade5];
[vfade5][v6]xfade=transition=fade:duration=1:offset=372.48000000000000000000[vfade6];
[vfade6][v7]xfade=transition=fade:duration=1:offset=416.13000000000000000000[vfade7];
[vfade7][v8]xfade=transition=fade:duration=1:offset=439.70000000000000000000[vfade8];
[vfade8][v9]xfade=transition=fade:duration=1:offset=484.70000000000000000000[vfade9];
[vfade9]format=yuv420p;

[0:a]atrim=start=5:end=58,asetpts=PTS-STARTPTS,atempo=1/.92000000000000000000[a0];
[1:a]atrim=start=59:end=107,asetpts=PTS-STARTPTS,atempo=1/.93000000000000000000[a1];
[2:a]atrim=start=109:end=199,asetpts=PTS-STARTPTS,atempo=1/.94000000000000000000[a2];
[3:a]atrim=start=220:end=267,asetpts=PTS-STARTPTS,atempo=1/.94000000000000000000[a3];
[4:a]atrim=start=273:end=355,asetpts=PTS-STARTPTS,atempo=1/.93000000000000000000[a4];
[5:a]atrim=start=356:end=443,asetpts=PTS-STARTPTS,atempo=1/.92000000000000000000[a5];
[6:a]atrim=start=452:end=499,asetpts=PTS-STARTPTS,atempo=1/.95000000000000000000[a6];
[7:a]atrim=start=518:end=545,asetpts=PTS-STARTPTS,atempo=1/.91000000000000000000[a7];
[8:a]atrim=start=561:end=611,asetpts=PTS-STARTPTS,atempo=1/.92000000000000000000[a8];
[9:a]atrim=start=620:end=660,asetpts=PTS-STARTPTS,atempo=1/.93000000000000000000[a9];

[a0]acopy[afade0];
[afade0][a1]acrossfade=d=1[afade1];
[afade1][a2]acrossfade=d=1[afade2];
[afade2][a3]acrossfade=d=1[afade3];
[afade3][a4]acrossfade=d=1[afade4];
[afade4][a5]acrossfade=d=1[afade5];
[afade5][a6]acrossfade=d=1[afade6];
[afade6][a7]acrossfade=d=1[afade7];
[afade7][a8]acrossfade=d=1[afade8];
[afade8][a9]acrossfade=d=1[afade9];
[afade9]acopy' 

-movflags +faststart video_cut.mp4