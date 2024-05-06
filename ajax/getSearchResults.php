<?php

require_once "../includes/config.php";
require_once "../includes/classes/SearchResultProvider.php";
require_once "../includes/classes/EntityProvider.php";
require_once "../includes/classes/Entity.php";
require_once "../includes/classes/PreviewProvider.php";
require_once "../includes/classes/FormSanitizer.php";

if (isset($_GET["term"]) && isset($_GET["username"])) {
    // $username = filter_var($_GET["username"], FILTER_SANITIZE_SPECIAL_CHARS);
    $username = FormSanitizer::sanitizeFormString($_GET["username"]);
    // $term = filter_var($_GET["term"], FILTER_SANITIZE_SPECIAL_CHARS);
    $term = FormSanitizer::sanitizeFormString($_GET["term"]);
    $srp = new SearchResultProvider($con, $username);
    echo $srp->getResults($term);
} else {
    echo "No term or username passed into file";
}
