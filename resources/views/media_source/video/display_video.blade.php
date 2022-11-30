<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width">
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/css/bootstrap.min.css">
    <style>
        .col-container {
            display: flex;
            width: 100%;
            max-width: 100%;
        }

        .col {
            padding: 16px;
            display: flex;
            justify-content: center;
            max-height: 100%;
        }
    </style>
</head>

<body>

    <div class="col-container">

        <div class="col col-lg-6 col-md-6 col-sm-12">
            <div class="row" style="display: flex;justify-content: center;max-width: 770px;max-height: 480px;">
                <h2 id="downloaded-heading">VIDEO DOWNLOADED</h2>
                <video controls="" autoplay="" name="media" width="100%" height="100%" id="my-video">
                    <source src="{{ $data->play_url . '/' . $data->name . '.' . $data->extension }}" type="video/mp4">
                </video>
            </div>

        </div>

        <div class="col col-lg-6 col-md-6 col-sm-12">
            <div class="row" style="display: flex;justify-content: center;max-width: 770px;max-height: 480px;">
                <h2 id="cutted-heading">VIDEO CUTTED</h2>

                <video controls="" autoplay="" name="media" width="100%" height="100%" id="my-video-2">
                    <source src="{{ $data->play_url . '/' . $data->name_cutted . '.' . $data->extension }}"
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
        $(document).ready(function() {

            displayVideoRatio1();
            displayVideoRatio2();


        });

        function displayVideoRatio1() {
            var vid = document.getElementById("my-video");
            const donwloadedHeading = document.getElementById('downloaded-heading');
            donwloadedHeading.textContent = `VIDEO DOWNLOADED (${vid.videoHeight}x${vid.videoWidth})`;
        }

        function displayVideoRatio2() {
            var vid = document.getElementById("my-video-2");
            const cuttedHeading = document.getElementById('cutted-heading');
            cuttedHeading.textContent = `VIDEO DOWNLOADED (${vid.videoHeight}x${vid.videoWidth})`;
        }
    </script>

</body>

</html>
