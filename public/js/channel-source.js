function fillChannelInfo() {
    var channelDetails = getChannelDetails();
    if (channelDetails === undefined) {
        return;
    }

    $("#channel-name").val(channelDetails.snippet.title);
    $("#channel-description").val(channelDetails.snippet.description);

    $("#channel-description").css({
        "animation-name": "colorChange",
        "animation-duration": "7s",
        "animation-iteration-count": "infinite",
    });
    $("#channel-name").css({
        "animation-name": "colorChange",
        "animation-duration": "7s",
        "animation-iteration-count": "infinite",
    });

    setTimeout(function () {
        $("#channel-name").css({"animation-name":"none","color":"black"});
        $("#channel-description").css({"animation-name": "none","color":"black"});
    }, 10000);
}

function getChannelDetails() {
    var channelUrl = $("#input-channel-url").val();

    if (channelUrl === undefined || channelUrl == "") {
        return;
    }

    var channelDetailsUrl =
        $("#base_url").val() + "/api/youtube/channel-details?url=" + channelUrl;
    var result;

    if (!channelDetailsUrl) return;
    $.ajax({
        method: "GET",
        url: channelDetailsUrl,
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
