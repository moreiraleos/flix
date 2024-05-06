<?php

require_once "../includes/config.php";


if (isset($_POST["videoId"]) && isset($_POST["username"])) {

    $videoId = filter_var($_POST["videoId"], FILTER_VALIDATE_INT);
    $username = htmlspecialchars($_POST["username"]);

    $query = $con->prepare("SELECT * FROM videoProgress WHERE username = :username AND videoId = :videoId");
    $query->bindValue(":username", $username);
    $query->bindValue(":videoId", $videoId);
    $query->execute();
    if ($query->rowCount() == 0) {
        $query = $con->prepare("INSERT INTO videoProgress(username, videoid) VALUES(:username, :videoId)");
        $query->bindValue(":username", $username);
        $query->bindValue(":videoId", $videoId);
        $query->execute();
        return;
    }
}
//  else {
//     echo "No videoId or username passed into file";
// }
