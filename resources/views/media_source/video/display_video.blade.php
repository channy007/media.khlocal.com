<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width">
    <link href="https://vjs.zencdn.net/7.18.1/video-js.css" rel="stylesheet" />
</head>

<body>

    {{-- <div class="container">
        <video id="my-video" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto">
            <source src="{{ $url }}" />
            <p class="vjs-no-js">
                To view this video please enable JavaScript, and consider upgrading to a
                web browser that
                <a href="https://videojs.com/html5-video-support/" target="_blank">supports
                    HTML5
                    video</a>
            </p>
        </video>
    </div> --}}

    <div class="container" style="display: flex;justify-content: center;">
        <video controls="" autoplay="" name="media">
            <source src="{{ $data->play_url.'/'.$data->name_cutted.'.'.$data->extension }}"
                type="video/mp4">
        </video>
    </div>


    <script src="https://vjs.zencdn.net/7.18.1/video.min.js"></script>

</body>

</html>
