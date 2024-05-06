<?php

class User
{
    private $con, $sqlData;

    public function __construct($con, $username)
    {
        $this->con = $con;
        $this->sqlData = $this->getByUsername($username);
    }

    public function getByUsername($username)
    {
        $query = $this->con->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindValue(":username", $username);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getFirstName()
    {
        return $this->sqlData["firstName"];
    }

    public function getLastName()
    {
        return $this->sqlData["lastName"];
    }

    public function getuserName()
    {
        return $this->sqlData["userName"];
    }

    public function getEmail()
    {
        return $this->sqlData["email"];
    }

    public function getIsSubscribed()
    {
        return $this->sqlData["isSubscribe"];
    }

    public function setIsSubscribed($value)
    {
        $query = $this->con->prepare("UPDATE users SET isSubscribe = :isSubscribe
                                    WHERE userName = :un");
        $query->bindValue(":isSubscribe", $value);
        $query->bindValue(":un", $this->getuserName());
        $query->execute();

        if ($query->execute()) {
            $this->sqlData["isSubscribe"] = $value;
            return true;
        }

        return false;
    }
}
