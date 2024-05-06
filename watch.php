<?php
$hideNav = true;

require_once "./includes/header.php";

if (!isset($_GET["id"])) {
    ErrorMessage::show("No ID passed into page");
}

$user = new User($con, $userLoggedIn);

if (!$user->getIsSubscribed()) {
    ErrorMessage::show("You must be subscribe to see this. <a href='profile.php'>Click here to subscribe</a>");
}

$entityId = filter_var($_GET["id"], FILTER_VALIDATE_INT);
$video = new Video($con, $entityId);
$video->incrementViews();

$upNextVideo = VideoProvider::getUpNext($con, $video);
?>

<div class="watchContainer">

    <div class="videoControls watchNav" data-id="<?= $video->getId() ?>" data-user="<?= $userLoggedIn ?>">
        <button><i class="fas fa-arrow-left"></i></button>
        <h1><?= $video->getTitle() ?></h1>
    </div>

    <div class="videoControls upNext" style="display:none">
        <button class="up"><i class="fas fa-redo"></i></button>
        <div class="upNextContainer">
            <h2>Up next:</h2>
            <h3><?= $upNextVideo->getTitle() ?></h3>
            <h3><?= $upNextVideo->getSeasonAndEpisode() ?></h3>

            <button class="playNext" data-id="<?= $upNextVideo->getId() ?>">
                <i class="fas fa-play"></i> Play
            </button>
        </div>
    </div>

    <video class="video" controls autoplay>
        <source src="<?= $video->getFilePath(); ?>" type="video/mp4">
    </video>
</div>