<?php

require_once "./includes/header.php";

if (!isset($_GET['id'])) {
    ErrorMessage::show("No id passed to page");
    return;
}

$categoryId = filter_var($_GET["id"], FILTER_VALIDATE_INT);

$preview = new PreviewProvider($con, $userLoggedIn);
echo $preview->createCategoryPreviewVideo($categoryId);

$containers = new CategoryContainers($con, $userLoggedIn);
echo $containers->showCategory($categoryId);
