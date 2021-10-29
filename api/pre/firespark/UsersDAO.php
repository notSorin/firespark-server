<?php
    require_once('DatabaseOperation.php');
    require_once('constants.php');
    require_once('User.php');

    class UsersDAO extends DatabaseOperation
    {
        function __construct()
        {
            parent::__construct();
        }

        function getUserByEmail($email)
        {
            $user = null;
           
            if($this->databaseConnection != null)
            {
                $sql = "select * from users where email = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("s", $email);
                
                $statement->execute();

                $result = $statement->get_result();

                if($result->num_rows == 1)
                {
                    $user = $result->fetch_object("User");
                }
            }

            return $user;
        }

        function getUserByUsername($username)
        {
            $user = null;
           
            if($this->databaseConnection != null)
            {
                $sql = "select * from users where username = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("s", $username);
                
                $statement->execute();

                $result = $statement->get_result();

                if($result->num_rows == 1)
                {
                    $user = $result->fetch_object("User");
                }
            }

            return $user;
        }

        function getUserByEmailAndPassword($email, $password)
        {
            $user = null;
           
            if($this->databaseConnection != null)
            {
                $tmpUser = $this->getUserByEmail($email);

                if(password_verify($password, $tmpUser->password))
                {
                    $user = $tmpUser;
                }
            }

            return $user;
        }

        //Returns a user that matches a certain username (ignoring case) and password, or null on error.
        function getUserByUsernameAndPassword($username, $password)
        {
            $user = null;

            if($this->databaseConnection != null)
            {
                $tmpUser = $this->getUserByUsername($username);

                if(password_verify($password, $tmpUser->password))
                {
                    $user = $tmpUser;
                }
            }

            return $user;
        }
    }
?>