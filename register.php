<?php

require_once "./includes/config.php";
require_once "./includes/classes/FormSanitizer.php";
require_once "./includes/classes/Constants.php";
require_once "./includes/classes/Account.php";

$account = new Account($con);

if (isset($_POST["submitButton"])) {

    $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
    $userName = FormSanitizer::sanitizeFormUserName($_POST["username"]);
    $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);
    $email2 = FormSanitizer::sanitizeFormEmail($_POST["email2"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
    $password2 = FormSanitizer::sanitizeFormPassword($_POST["password2"]);

    $success = $account->register($firstName, $lastName, $userName, $email, $email2, $password, $password2);

    if ($success) {
        //   Store session
        $_SESSION["login"] = true;
        $_SESSION["userLoggedIn"] = $userName;
        header("Location: index.php");
    }
}

function getInputValue($name)
{
    if (isset($_POST[$name])) {
        echo $_POST[$name];
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./assets/style/style.css" />
    <title>Welcome to Flix</title>
</head>

<body>
    <div class="singInContainer">
        <div class="column">
            <div class="header">
                <img src="./assets/images/logo.png" alt="Logo" title="Logo" />
                <h3>Sign Up</h3>
                <span>to continue to VideoTube</span>
            </div>
            <form method="POST">
                <?= $account->getError(Contants::$firstNameCharacters) ?>
                <input type="text" name="firstName" placeholder="First name" value="<?= getInputValue("firstName") ?>" required />

                <?= $account->getError(Contants::$lastNameCharacters) ?>
                <input type="text" name="lastName" placeholder="Last name" value="<?= getInputValue("lastName") ?>" required />

                <?= $account->getError(Contants::$userNameCharacters) ?>
                <?= $account->getError(Contants::$userNameTaken) ?>
                <input type="text" name="username" placeholder="Username" value="<?= getInputValue("username") ?>" required />

                <?= $account->getError(Contants::$emailsDontMatch) ?>
                <?= $account->getError(Contants::$emailInvalid) ?>
                <?= $account->getError(Contants::$emailTaken) ?>
                <input type="email" name="email" placeholder="Email" value="<?= getInputValue("email") ?>" required />
                <input type="email" name="email2" placeholder="Confirm email" value="<?= getInputValue("email2") ?>" required />

                <?= $account->getError(Contants::$passwordDontMatch) ?>
                <?= $account->getError(Contants::$passwordLength) ?>
                <input type="password" name="password" placeholder="Password"  required />
                <input type="password" name="password2" placeholder="Confirm password"  required />

                <input type="submit" name="submitButton" value="SUBMIT" />
            </form>
            <a href="login.php" class="singInMessage">Already have an account? Sing in here!</a>
            <div class="efeito"></div>
        </div>
    </div>
</body>

</html>