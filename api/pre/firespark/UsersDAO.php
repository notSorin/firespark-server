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

        function getUserByEmailAndPassword($email, $password)
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
                    $tmpUser = $result->fetch_object("User");

                    if(password_verify($password, $tmpUser->password))
                    {
                        $user = $tmpUser;
                    }
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
                $sql = "select * from users where lower(username) = lower(?);";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("s", $username);
                
                $statement->execute();

                $result = $statement->get_result();

                if($result->num_rows == 1)
                {
                    $tmpUser = $result->fetch_object("User");

                    if(password_verify($password, $tmpUser->password))
                    {
                        $user = $tmpUser;
                    }
                }
            }

            return $user;
        }
    }
?>