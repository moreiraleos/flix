<?php

require_once "../includes/config.php";


if (isset($_POST["videoId"]) && isset($_POST["username"]) && isset($_POST["progress"])) {

    $videoId = filter_var($_POST["videoId"], FILTER_VALIDATE_INT);
    $username = htmlspecialchars($_POST["username"]);
    $progress = htmlspecialchars($_POST["progress"]);


    $query = $con->prepare("UPDATE videoProgress SET progress=:progress WHERE username=:username AND videoId=:videoId");
    $query->bindValue(":progress", $progress);
    $query->bindValue(":username", $username);
    $query->bindValue(":videoId", $videoId);
    $query->execute();
    return;
} 
// else {
//     echo "No videoId or username passed into file";
// }
