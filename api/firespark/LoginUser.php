<?php
    require_once('DatabaseOperation.php');

    class LoginUser extends DatabaseOperation
    {
        function __construct()
        {
            parent::__construct();
        }
        
        //Returns an array with the user's data on success, null otherwise.
        function loginUser($emailOrUsername, $password)
        {
            $userData = null;

            if(filter_var($emailOrUsername, FILTER_VALIDATE_EMAIL)) //It's an email.
            {
                $userData = $this->loginWithEmailAndPassword($emailOrUsername, $password);
            }
            else //Should be an username.
            {
                $userData = $this->loginWithUsernameAndPassword($emailOrUsername, $password);
            }

            return $userData;
        }

        private function loginWithEmailAndPassword($email, $password)
        {
            $userData = null;
           
            if($this->databaseConnection != null)
            {
                $sql = "select userid, password from users where email = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("s", $email);
                
                $statement->execute();

                $result = $statement->get_result();
                $userData = $this->processLoginResult($result, $password);
            }

            return $userData;
        }

        private function loginWithUsernameAndPassword($username, $password)
        {
            $userData = null;

            if($this->databaseConnection != null)
            {
                $sql = "select userid, password from users where lower(username) = lower(?);";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("s", $username);
                
                $statement->execute();

                $result = $statement->get_result();
                $userData = $this->processLoginResult($result, $password);
            }

            return $userData;
        }

        private function processLoginResult($result, $password)
        {
            $userData = null;

            if($result->num_rows == 1)
            {
                $row = $result->fetch_assoc();
                $hash = $row['password'];

                if(password_verify($password, $hash))
                {
                    $userData = array(
                        "userid" => $row['userid']
                    );
                }
            }

            return $userData;
        }

        function containsRequiredKeys($keysArray)
        {
            return array_key_exists(KEY_EMAIL_OR_USERNAME, $keysArray) && array_key_exists(KEY_PASSWORD, $keysArray);
        }
    }
?>