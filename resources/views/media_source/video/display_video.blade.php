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
                <h2>VIDEO DOWNLOADED</h2>
                <video controls="" autoplay="" name="media" width="100%" height="100%">
                    <source src="{{ $data->play_url . '/' . $data->name . '.' . $data->extension }}" type="video/mp4">
                </video>
            </div>
            
        </div>

        <div class="col col-lg-6 col-md-6 col-sm-12">
            <div class="row" style="display: flex;justify-content: center;max-width: 770px;max-height: 480px;">
                <h2>VIDEO CUTTED</h2>

                <video controls="" autoplay="" name="media" width="100%" height="100%" >
                    <source src="{{ $data->play_url.'/'.$data->name_cutted.'.'.$data->extension }}"
                        type="video/mp4">
                </video>
            </div>
            
        </div>

    </div>

</body>

</html>
