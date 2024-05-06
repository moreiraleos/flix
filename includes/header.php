<?php

require_once "./includes/config.php";
require_once "./includes/classes/PreviewProvider.php";
require_once "./includes/classes/CategoryContainers.php";
require_once "./includes/classes/Entity.php";
require_once "./includes/classes/EntityProvider.php";
require_once "./includes/classes/ErrorMessage.php";
require_once "./includes/classes/SeasonProvider.php";
require_once "./includes/classes/Season.php";
require_once "./includes/classes/Video.php";
require_once "./includes/classes/VideoProvider.php";
require_once "./includes/classes/User.php";

if (!isset($_SESSION["userLoggedIn"])) {
    header("location: login.php");
}
$userLoggedIn = $_SESSION["userLoggedIn"];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Welcome to Flix</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha512-bnIvzh6FU75ZKxp0GXLH9bewza/OIw6dLVh9ICg0gogclmYGguQJWl8U30WpbsGTqbIiAwxTsbe76DErLq5EDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha512-9BwLAVqqt6oFdXohPLuNHxhx36BVj5uGSGmizkmGkgl3uMSgNalKc/smum+GJU/TTP0jy0+ruwC3xNAk3F759A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="./assets/js/script.js"></script>
    <link rel="stylesheet" type="text/css" href="./assets/style/style.css" />
</head>

<body>
    <div class="wrapper">

        <?php
        if (!isset($hideNav)) {
            include_once "includes/navbar.php";
        }
        ?>