function fillSourceInfo() {
    var videoDetails = getYoutubeVideoDetails();
    if (videoDetails === undefined) {
        return;
    }
    alert(videoDetails);

    return;

    $("#source-name").val(videoDetails.items[0].snippet.title);
    $("#source-text").val(videoDetails.items[0].snippet.description);

    $("#source-name").css({
        "animation-name": "colorChange",
        "animation-duration": "7s",
        "animation-iteration-count": "infinite",
    });
    $("#source-text").css({
        "animation-name": "colorChange",
        "animation-duration": "7s",
        "animation-iteration-count": "infinite",
    });

    setTimeout(function () {
        $("#source-text").css({"animation-name":"none","color":"black"});
        $("#source-name").css({"animation-name": "none","color":"black"});
    }, 10000);
}

function getYoutubeVideoDetails() {
    var videoUrl = $(".input-source-url").val();
    var videoDetailsURL =
        $("#base_url").val() + "/api/youtube/video-details?url=" + videoUrl;
    var result;

    if (!videoUrl) return;
    $.ajax({
        method: "GET",
        url: videoDetailsURL,
        // data: new FormData(this),
        // dataType: 'JSON',
        async: false,
        contentType: false,
        cache: false,
        processData: false,
        timeout: 0,
        success: function (data) {
            result = data.data;
        },
        failure: function (response) {
            console.log("===================== failure", response);
        },
        error: function (response) {
            console.log("===================== error", response);
        },
    });
    return result;
}
