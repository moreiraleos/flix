<?php

require_once "./PayPal-PHP-SDK/autoload.php";


$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'AVcIAOBvAEtPjHnsav8Fx-upw5lS3BjXurIYgLPv9IoZaHp9GurEASiIa3KfQh2hqO3y9vGvQHcrmvOt', //ClientID
        'EEXvb5x0aI4q5i8slWqGdr4t11rpsCGU0bm1VfnhzsOsTle2aQ29m0tytOQ18eFLimao3U3P3CYTjLun' //ClientSecret
    )
);
