$("document").ready(function () {
  function volumeToggle(button) {
    var muted = $(".previewVideo").prop("muted");
    $(".previewVideo").prop("muted", !muted);

    $(button).find("i").toggleClass("fa-volume-mute");
    $(button).find("i").toggleClass("fa-volume-up");
  }

  function previewEnded() {
    $(".previewVideo").toggle();
    $(".previewImage").toggle();
  }

  function goBack() {
    window.history.back();
  }

  function startHideTimer() {
    var timeOut = null;
    $(document).on("mousemove", function () {
      clearTimeout(timeOut);

      $(".watchNav").fadeIn();

      timeOut = setTimeout(function () {
        $(".watchNav").fadeOut();
      }, 2000);
    });
  }

  function initVideo(videoId, username) {
    startHideTimer();
    setStartTime(videoId, username);
    updateProgressTimer(videoId, username);
  }

  function updateProgressTimer(videoId, username) {
    addDuration(videoId, username);

    var timer;
    $("video")
      .on("playing", function (event) {
        window.clearInterval(timer);
        timer = window.setInterval(function () {
          updateProgress(videoId, username, event.target.currentTime);
        }, 3000);
      })
      .on("pause", function () {
        window.clearInterval(timer);
      })
      .on("ended", function () {
        setFinished(videoId, username);
        window.clearInterval(timer);
      });
  }

  function addDuration(videoId, username) {
    $.post(
      "ajax/addDuration.php",
      {
        videoId: videoId,
        username: username,
      },
      function (data) {
        if (data !== null && data !== "") {
          alert(data);
        }
      }
    );
  }

  function updateProgress(videoId, username, progress) {
    $.post(
      "ajax/updateDuration.php",
      {
        videoId: videoId,
        username: username,
        progress: progress,
      },
      function (data) {
        if (data !== null && data !== "") {
          alert(data);
        }
      }
    );
  }

  function setFinished(videoId, username) {
    $.post(
      "ajax/setFinished.php",
      {
        videoId: videoId,
        username: username,
      },
      function (data) {
        if (data !== null && data !== "") {
          alert(data);
        }
      }
    );
  }

  function setStartTime(videoId, username) {
    $.post(
      "ajax/getProgress.php",
      {
        videoId: videoId,
        username: username,
      },
      function (data) {
        if (isNaN(data)) {
          alert(data);
          return;
        }

        $("video").on("canplay", function () {
          this.currentTime = data;
          $("video").off("canplay");
        });
      }
    );
  }

  function restartVideo() {
    $("video")[0].currentTime = 0;
    $("video")[0].play();
    $(".upNext").fadeOut();
  }

  function watchVideo(videoId) {
    window.location.href = "watch.php?id=" + videoId;
  }

  function showUpNext() {
    $(".upNext").fadeIn();
  }

  $(".upNext .up").click(function () {
    restartVideo();
  });

  $(".upNext .playNext").click(function () {
    var videoId = $(".playNext").data("id");
    watchVideo(videoId);
  });

  var buttonPlay = $(".buttons button")[0];
  var buttonMuted = $(".buttons button")[1];
  var video = $(".previewVideo");
  var buttonWatchPageBack = $(".videoControls.watchNav button");
  var videoId = $(".watchNav").data("id");
  var userLogged = $(".watchNav").data("user");

  $(buttonMuted).click(function () {
    volumeToggle(this);
  });

  $(video).on("ended", function () {
    previewEnded();
  });

  $(".watchContainer .video").on("ended", function () {
    showUpNext();
  });

  // Scroll videos horizontal - seasons
  $(".videos,.entities").on("wheel", function (e) {
    var delta =
      e.originalEvent.deltaY ||
      e.originalEvent.detail ||
      e.originalEvent.wheelDelta;
    this.scrollLeft += (delta < 0 ? -1 : 1) * 30;
    e.preventDefault();
  });

  $(buttonWatchPageBack).click(function () {
    goBack();
  });

  $(buttonPlay).click(function () {
    var videoId = $(buttonPlay).data("id");
    watchVideo(videoId);
  });

  $(document).on("scroll", function () {
    $(window).scroll(function () {
      $(".topBar").toggleClass(
        "background",
        $(window).scrollTop() > $(".topBar").height()
      );
    });
  });

  initVideo(videoId, userLogged);
});
