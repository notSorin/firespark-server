<?php
    require_once('DatabaseOperation.php');

    class RegisterUser extends DatabaseOperation
    {
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

        function isEmailUsable($email)
        {
            $isUsable = false;

            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $isUsable = !$this->isEmailUsed($email);
            }

            return $isUsable;
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