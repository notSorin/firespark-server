<?php
    require_once('DatabaseOperation.php');

    class RegisterUser extends DatabaseOperation
    {
        private $PASSWORD_REGEX = "/^(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S{8,20}$/";
        private $USERNAME_REGEX = "/^[a-zA-Z0-9]{1,20}$/";
        private $FIRST_LAST_NAME_REGEX = "/^[a-zA-Z0-9 ]{1,30}$/";

        function createUser($email, $password, $username, $firstlastname)
        {
            $success = false;

            if($this->databaseConnection != null)
            {
                $sql = "insert into users (email, password, username, firstlastname) VALUES (?, ?, ?, ?);";
                $statement = $this->databaseConnection->prepare($sql);
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $statement->bind_param("ssss", $email, $passwordHash, $username, $firstlastname);
                
                $success = $statement->execute();
            }

            return $success;
        }

        function containsRequiredKeys($keysArray)
        {
            return array_key_exists(KEY_EMAIL, $keysArray) && array_key_exists(KEY_PASSWORD, $keysArray) &&
                array_key_exists(KEY_USERNAME, $keysArray) && array_key_exists(KEY_FIRSTLASTNAME, $keysArray);
        }

        private function isEmailUsed($email)
        {
            $isUsed = false;

            if($this->databaseConnection != null)
            {
                $sql = "select * from users where email = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("s", $email);
                
                $statement->execute();
                $rowsFound = $statement->get_result()->num_rows;

                $isUsed = $rowsFound != 0;
            }

            return $isUsed;
        }

        //Returns true if an email can be used for registering in the network, false otherwise.
        function isEmailUsable($email)
        {
            $isUsable = false;

            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $isUsable = !$this->isEmailUsed($email);
            }

            return $isUsable;
        }

        //Returns true if a password can be used for registering in the network, false otherwise.
        function isPasswordUsable($password)
        {
            return preg_match($this->PASSWORD_REGEX, $password);
        }

        private function isUsernameUsed($username)
        {
            $isUsed = false;

            if($this->databaseConnection != null)
            {
                $sql = "select * from users where username = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("s", $username);
                
                $statement->execute();
                $rowsFound = $statement->get_result()->num_rows;

                $isUsed = $rowsFound != 0;
            }

            return $isUsed;
        }

        //Returns true if a username can be used for registering in the network, false otherwise.
        function isUsernameUsable($username)
        {
            $isUsable = false;

            if(preg_match($this->USERNAME_REGEX, $username))
            {
                $isUsable = !$this->isUsernameUsed($username);
            }

            return $isUsable;
        }

        //Returns true if a first and last name can be used for registering in the network, false otherwise.
        function isFirstLastNameUsable($firstlastname)
        {
            return preg_match($this->FIRST_LAST_NAME_REGEX, $firstlastname);
        }
    }
?>