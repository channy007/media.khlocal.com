<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width">
    {{-- <link href="https://vjs.zencdn.net/7.18.1/video-js.css" rel="stylesheet" /> --}}
</head>

<body>

    <div class="container" style="display: flex;justify-content: center; max-width: 100%;max-height: 750px;">
        <video controls="" autoplay="" name="media">
            <source src="{{ $data->play_url.'/'.$data->name.'.'.$data->extension }}"
                type="video/mp4">
        </video>
    </div>


    {{-- <script src="https://vjs.zencdn.net/7.18.1/video.min.js"></script> --}}

</body>

</html>
