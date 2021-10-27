<?php
    require_once('DatabaseOperation.php');

    class RegisterUser extends DatabaseOperation
    {
        function createUser($email, $password, $username, $firstlastname)
        {
            $success = false;

            if($this->databaseConnection != null)
            {
                $sql = "INSERT INTO users (email, password, username, firstlastname) VALUES (?, ?, ?, ?);";
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

        function isEmailUsable($email)
        {
            //todo
        }

        function isPasswordUsable($email)
        {
            //todo
        }

        function isUsernameUsable($email)
        {
            //todo
        }

        function isFirstLastNameUsable($email)
        {
            //todo
        }
    }
?>