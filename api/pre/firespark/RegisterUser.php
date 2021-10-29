<?php
    require_once('UsersDAO.php');

    class RegisterUser
    {
        private $PASSWORD_REGEX = "/^(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S{8,20}$/";
        private $USERNAME_REGEX = "/^[a-zA-Z0-9]{1,20}$/";
        private $FIRST_LAST_NAME_REGEX = "/^[a-zA-Z0-9 ]{1,30}$/";

        private $usersDAO;

        function __construct()
        {
            $this->usersDAO = new UsersDAO();
        }

        function registerUser($email, $password, $username, $firstlastname)
        {
            return $this->usersDAO->insertUser($email, $password, $username, $firstlastname);
        }

        function containsRequiredKeys($keysArray)
        {
            return array_key_exists(KEY_EMAIL, $keysArray) && array_key_exists(KEY_PASSWORD, $keysArray) &&
                array_key_exists(KEY_USERNAME, $keysArray) && array_key_exists(KEY_FIRSTLASTNAME, $keysArray);
        }

        //Returns true if an email can be used for registering in the network, false otherwise.
        function isEmailUsable($email)
        {
            $isUsable = false;

            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $isUsable = !$this->usersDAO->isEmailUsed($email);
            }

            return $isUsable;
        }

        //Returns true if a password can be used for registering in the network, false otherwise.
        function isPasswordUsable($password)
        {
            return preg_match($this->PASSWORD_REGEX, $password);
        }

        //Returns true if a username can be used for registering in the network, false otherwise.
        function isUsernameUsable($username)
        {
            $isUsable = false;

            if(preg_match($this->USERNAME_REGEX, $username))
            {
                $isUsable = !$this->usersDAO->isUsernameUsed($username);
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