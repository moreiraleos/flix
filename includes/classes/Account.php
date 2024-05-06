<?php

class Account
{
    private $con;
    private $errorArray = array();
    public function __construct($con)
    {
        $this->con = $con;
    }

    public function updateDetails($fn, $ln, $em, $un)
    {
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateNewEmail($em, $un);

        if (empty($this->errorArray)) {
            $query = $this->con->prepare("UPDATE users SET firstName = :fn, lastName = :ln, email = :em 
            WhERE username = :un");
            $query->bindValue(":fn", $fn);
            $query->bindValue(":ln", $ln);
            $query->bindValue(":em", $em);
            $query->bindValue(":un", $un);

            return $query->execute();
        }

        return false;
    }

    public function register($fn, $ln, $un, $em, $em2, $pw, $pw2): bool
    {
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateUserName($un);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);

        if (empty($this->errorArray)) {
            return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
        }

        return false;
    }

    public function login($un, $pw)
    {
        $this->validateUserName($un);
        $query = $this->con->prepare("SELECT * FROM users WHERE userName = :un");
        $query->bindValue("un", $un);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if ($query->rowCount() == 1) {
            $hash = $user["password"];
            $pass = password_verify($pw, $hash);
            if ($pass) {
                return true;
            }
        }
        array_push($this->errorArray, Contants::$loginFailed);
        return false;
    }

    private function insertUserDetails($fn, $ln, $un, $em, $pw)
    {
        $pw = password_hash($pw, PASSWORD_DEFAULT);
        $query = $this->con->prepare(
            "INSERT INTO users(firstName,lastName, userName, email, password) 
            VALUES(:firstName, :lastName, :userName, :email, :password)"
        );
        $query->bindValue(":firstName", $fn);
        $query->bindValue(":lastName", $ln);
        $query->bindValue(":userName", $un);
        $query->bindValue(":email", $em);
        $query->bindValue(":password", $pw);
        // var_dump($query->errorInfo());

        return $query->execute();
    }

    private function validateFirstName($fn)
    {
        if (strlen($fn) < 2 || strlen($fn) > 25) {
            array_push($this->errorArray, Contants::$firstNameCharacters);
        }
    }
    private function validateLastName($ln)
    {
        if (strlen($ln) < 2 || strlen($ln) > 25) {
            array_push($this->errorArray, Contants::$lastNameCharacters);
        }
    }

    private function validateUserName($un)
    {
        if (strlen($un) < 2 || strlen($un) > 25) {
            array_push($this->errorArray, Contants::$userNameCharacters);
            return;
        }

        $query = $this->con->prepare("SELECT id FROM users WHERE userName = :un");
        $query->bindValue(":un", $un);
        $query->execute();

        if ($query->rowCount() != 0) {
            array_push($this->errorArray, Contants::$userNameTaken);
            return;
        }
    }

    private function validateEmails($em1, $em2)
    {
        if (!filter_var($em1, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Contants::$emailInvalid);
            return;
        }

        if ($em1 != $em2) {
            array_push($this->errorArray, Contants::$emailsDontMatch);
            return;
        }

        $query = $this->con->prepare("SELECT email FROM users WHERE email = :email");
        $query->bindValue("email", $em1);
        $query->execute();

        if ($query->rowCount() != 0) {
            array_push($this->errorArray, Contants::$emailTaken);
            return;
        }
    }

    private function validateNewEmail($em1, $un)
    {
        if (!filter_var($em1, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Contants::$emailInvalid);
            return;
        }

        $query = $this->con->prepare("SELECT email FROM users WHERE email = :email AND username != :un");
        $query->bindValue("email", $em1);
        $query->bindValue("un", $un);
        $query->execute();

        if ($query->rowCount() != 0) {
            array_push($this->errorArray, Contants::$emailTaken);
            return;
        }
    }

    private function validatePasswords($pw1, $pw2)
    {
        if (strlen($pw1) < 5 || strlen($pw1) > 25) {
            array_push($this->errorArray, Contants::$passwordLength);
            return;
        }

        if ($pw1 != $pw2) {
            array_push($this->errorArray, Contants::$passwordDontMatch);
            return;
        }
    }

    public function getError($error)
    {
        if (in_array($error, $this->errorArray)) {
            return "<span class='errormessage'>{$error}</span>";
        }
    }

    public function getFirstError()
    {
        if (!empty($this->errorArray)) {
            return $this->errorArray[0];
        }
    }

    public function updatePassword($oldPw, $pw, $pw2, $un)
    {
        $this->validateOldPassword($oldPw, $un);
        $this->validatePasswords($pw, $pw2);

        if (empty($this->errorArray)) {
            $query = $this->con->prepare("UPDATE users SET password = :pw WhERE username = :un");
            $pw = password_hash($pw, PASSWORD_DEFAULT);
            $query->bindValue(":pw", $pw);
            $query->bindValue(":un", $un);
            return $query->execute();
        }

        return false;
    }

    public function validateOldPassword($oldPw, $un)
    {
        $query = $this->con->prepare("SELECT * FROM users WHERE userName = :un");
        $query->bindValue(":un", $un);
        $query->execute();
        $pw = $query->fetch(PDO::FETCH_ASSOC)["password"];

        $validate = password_verify($oldPw, $pw);
        if (!$validate) {
            array_push($this->errorArray, Contants::$passwordIncorrect);
        }
    }
}
