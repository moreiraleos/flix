<?php
require_once "./includes/config.php";
require_once "./includes/classes/FormSanitizer.php";
require_once "./includes/classes/Constants.php";
require_once "./includes/classes/Account.php";

$account = new Account($con);

if (isset($_POST["submitButton"])) {

    $userName = FormSanitizer::sanitizeFormUserName($_POST["username"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);


    $success = $account->login($userName, $password);

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
                <h3>Sign In</h3>
                <span>to continue to VideoTube</span>
            </div>
            <form method="POST" action="">
                <?= $account->getError(Contants::$loginFailed) ?>
                <input type="text" name="username" placeholder="Username" value="<?= getInputValue("username") ?>" required />
                <input type="password" name="password" placeholder="Password" required />
                <input type="submit" name="submitButton" value="SUBMIT" />
            </form>
            <a href="register.php" class="singInMessage">Need an account? Sing up here!</a>
            <div class="efeito"></div>
        </div>
    </div>
</body>

</html>