<?php

require_once "../includes/config.php";


if (isset($_POST["videoId"]) && isset($_POST["username"])) {

    $videoId = filter_var($_POST["videoId"], FILTER_VALIDATE_INT);
    $username = htmlspecialchars($_POST["username"]);

    $query = $con->prepare("SELECT progress FROM videoProgress WHERE username = :username AND videoId = :videoId");
    $query->bindValue(":username", $username);
    $query->bindValue(":videoId", $videoId);
    $query->execute();
    echo $query->fetchColumn();
}
