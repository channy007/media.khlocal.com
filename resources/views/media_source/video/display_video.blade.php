<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width">
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/css/bootstrap.min.css">
    <style>
        .col-container {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            max-width: 100%;
        }

        .col {
            padding: 16px;
            display: flex;
            justify-content: center;
            max-height: 100%;
        }

        .flex-item-left {
            padding: 10px;
            flex: 50%;
        }

        .flex-item-right {
            padding: 10px;
            flex: 50%;
        }
        video {
            width: 95%;
            height: 100%;
        }

        /* Responsive layout - makes a one column-layout instead of a two-column layout */
        @media (max-width: 800px) {
            .flex-item-right, .flex-item-left {
                flex: 100%;
                margin: 15px;
            }
            .box{
                height: 75px;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="col-container">

        <div class="col col-lg-6 col-md-12 col-sm-12 flex-item-left">
            <div class="row" style="display: flex;justify-content: center;max-width: 750px;max-height: 480px;">
                <h2 id="downloaded-heading">VIDEO DOWNLOADED</h2>
                <video controls="" autoplay="" name="media" id="my-video-downloaded" controls>
                    <source src="{{ url('/') . '/video/' . $data->name . '.' . $data->extension }}" type="video/mp4">
                </video>
            </div>

        </div>

        <div class="box"></div>

        <div class="col col-lg-6 col-md-12 col-sm-12 flex-item-right">
            <div class="row" style="display: flex;justify-content: center;max-width: 750px;max-height: 480px;">
                <h2 id="cutted-heading">VIDEO CUTTED</h2>

                <video controls="" autoplay="" name="media" id="my-video-cutted" controls>
                    <source src="{{ url('/') . '/video/' . $data->name_cutted . '.' . $data->extension }}"
                        type="video/mp4">
                </video>
            </div>

        </div>
    </div>

    {{-- <div class="row" style="margin-top: 25px;">

        <button onclick="displayVideoRatio1()">Show Ratio 1</button>
        <button onclick="displayVideoRatio2()">Show Ratio 2</button>

    </div> --}}

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script type="text/javascript">

        $('#my-video-downloaded').on('loadeddata',function(){
            displayVideoDownloadedRatio();
        });

        $('#my-video-cutted').on('loadeddata',function(){
            displayVideoCuttedRatio();
        });
        

        function displayVideoDownloadedRatio() {
            var vid = document.getElementById("my-video-downloaded");
            const donwloadedHeading = document.getElementById('downloaded-heading');
            donwloadedHeading.textContent = `VIDEO DOWNLOADED (${vid.videoHeight}x${vid.videoWidth})`;
        }

        function displayVideoCuttedRatio() {
            var vid = document.getElementById("my-video-cutted");
            const cuttedHeading = document.getElementById('cutted-heading');
            cuttedHeading.textContent = `VIDEO CUTTED (${vid.videoHeight}x${vid.videoWidth})`;
        }
    </script>

</body>

</html>
