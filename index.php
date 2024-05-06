<?php
require_once "./includes/header.php";
$preview = new PreviewProvider($con, $userLoggedIn);
echo $preview->createPreviewVideo();

$containers = new CategoryContainers($con, $userLoggedIn);
echo $containers->showAllCategories();
