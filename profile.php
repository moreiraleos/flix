<?php
require_once "./includes/header.php";
require_once "./includes/paypalConfig.php";
require_once "./includes/classes/Account.php";
require_once "./includes/classes/FormSanitizer.php";
require_once "./includes/classes/Constants.php";
require_once "./includes/classes/BillingDetails.php";

$user = new User($con, $userLoggedIn);

$detailsMessage = "";
$passwordMessage = "";
$subscriptionMessage = "";
if (isset($_POST["saveDetailsButton"])) {
    $account = new Account($con);

    // $oldPassword = FormSanitizer::sanitizeFormPassword($_POST["oldPassword"]);
    // $newPassword = FormSanitizer::sanitizeFormPassword($_POST["newPassword"]);
    // $newPassword2 = FormSanitizer::sanitizeFormPassword($_POST["newPassword2"]);

    $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
    $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);

    if ($account->updateDetails($firstName, $lastName, $email, $userLoggedIn)) {
        $detailsMessage = "<div class='alertSuccess'>
                            Details updated successfuly!
                        </div>";
    } else {
        $errorMessage = $account->getFirstError();
        $detailsMessage = "<div class='alertError'>
                            $errorMessage
                        </div>";
    }
}

if (isset($_POST["savePasswordButton"])) {
    $account = new Account($con);

    $oldPassword = FormSanitizer::sanitizeFormPassword($_POST["oldPassword"]);
    $newPassword = FormSanitizer::sanitizeFormPassword($_POST["newPassword"]);
    $newPassword2 = FormSanitizer::sanitizeFormPassword($_POST["newPassword2"]);

    if ($account->updatePassword($oldPassword, $newPassword, $newPassword2, $userLoggedIn)) {
        $passwordMessage = "<div class='alertSuccess'>
                            Password updated successfuly!
                        </div>";
    } else {
        $errorMessage = $account->getFirstError();
        $passwordMessage = "<div class='alertError'>
                            $errorMessage
                        </div>";
    }
}

if (isset($_GET["success"]) && $_GET["success"] == true) {
    $token = $_GET["token"];
    $agreement = new \PayPal\Api\Agreement();
    $subscriptionMessage = "<div class='alertError'>
            Something went wrong!
            </div>";

    try {
        // Execute agreement
        $agreement->execute($token, $apiContext);

        $result = BillingDetails::insertDetails($con, $agreement, $token, $userLoggedIn);
        // Update user's account status
        $result = $result && $user->setIsSubscribed(1);

        if ($result) {
            $subscriptionMessage = "<div class='alertSuccess'>
                                    You're all signed up!
                                </div>";
        }
    } catch (PayPal\Exception\PayPalConnectionException $ex) {
        // echo $ex->getCode();
        // echo $ex->getData();
        // die($ex);
        $subscriptionMessage = "<div class='alertError'>
        User cancelled or something went wrong!
    </div>";
    } catch (Exception $ex) {
        // die($ex);
        $subscriptionMessage = "<div class='alertError'>
        User cancelled or something went wrong!
    </div>";
    }
} else if (isset($_GET["success"]) && $_GET["success"] == false) {
    $subscriptionMessage = "<div class='alertError'>
                            User cancelled or something went wrong!
                        </div>";
}
?>
<div class="settingsContainer column">
    <div class="formSection">
        <form method="post">
            <h2>User details</h2>
            <?php

            $firstName = isset($_POST["firstName"]) ? $_POST["firstName"] : $user->getFirstName();
            $lastName =  isset($_POST["lastName"]) ? $_POST["lastName"] : $user->getLastName();
            $email = isset($_POST["email"]) ? $_POST["email"] : $user->getEmail();
            ?>
            <input type="text" name="firstName" placeholder="First name" value="<?= $firstName ?>">
            <input type="text" name="lastName" placeholder="Last name" value="<?= $lastName ?>">
            <input type="email" name="email" placeholder="Email" value="<?= $email ?>">
            <?= $detailsMessage; ?>
            <input type="submit" name="saveDetailsButton" value="Save">
        </form>
    </div>
    <div class="formSection">
        <form method="post">
            <h2>Update password</h2>
            <input type="password" name="oldPassword" placeholder="Old password">
            <input type="password" name="newPassword" placeholder="new password">
            <input type="password" name="newPassword2" placeholder="Confirme new password">
            <div class="message">
                <?= $passwordMessage; ?>
            </div>
            <input type="submit" name="savePasswordButton" value="Save">
        </form>
    </div>
    <div class="formSection">
        <h2>Subscription</h2>
        <div class="message">
            <?= $subscriptionMessage; ?>
        </div>
        <?php
        if ($user->getIsSubscribed()) {
            echo "<h3>You are subscribe! Go to PayPal to cancel.</h3>";
        } else {
            echo "<a href='billing.php'>Subscribe to Reeceflix.</a>";
        }
        ?>
    </div>
</div>