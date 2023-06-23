
// 2. This code loads the IFrame Player API code asynchronously.
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var player;
function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        height: '360',
        width: '640',
        videoId: 'isTo5kISXMg',
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        },
        playerVars: {
            'rel': 0,    //연관동영상 표시여부(0:표시안함)
            'controls': 0,    //플레이어 컨트롤러 표시여부(0:표시안함)
            // 'autoplay' : 1,   //자동재생 여부(1:자동재생 함, mute와 함께 설정)
            // 'mute' : 1,   //음소거여부(1:음소거 함)
            // 'loop' : 1,    //반복재생여부(1:반복재생 함)
            'playsinline' : 1,    //iOS환경에서 전체화면으로 재생하지 않게
            // 'playlist' : 'M7lc1UVf-VE'   //재생할 영상 리스트
        },
    });
}

// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
    event.target.playVideo();
}

// 5. The API calls this function when the player's state changes.
//    The function indicates that when playing a video (state=1),
//    the player should play for six seconds and then stop.
var done = false;
function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.PLAYING && !done) {
        setTimeout(stopVideo, 6000);
        done = true;
    }
}
function stopVideo() {
    player.stopVideo();
}
