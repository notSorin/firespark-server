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

        function getUserByEmail($email, $includeFollowers = true, $includeFollowing = true)
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

                    if($includeFollowers)
                    {
                        $user->followers = $this->getUserFollowers($user->userid);
                    }

                    if($includeFollowing)
                    {
                        $user->following = $this->getUserFollowing($user->userid);
                    }
                }
            }

            return $user;
        }

        function getUserFollowers($userId)
        {
            $followers = [];
           
            if($this->databaseConnection != null)
            {
                $sql = "select userid from followers where followeeid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $userId);
                
                $statement->execute();

                $result = $statement->get_result();

                while($row = mysqli_fetch_assoc($result))
                {
                    $followers[] = $row["userid"];
                }
            }

            return $followers;
        }

        function getUserFollowing($userId)
        {
            $following = [];
           
            if($this->databaseConnection != null)
            {
                $sql = "select followeeid from followers where userid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $userId);
                
                $statement->execute();

                $result = $statement->get_result();

                while($row = mysqli_fetch_assoc($result))
                {
                    $following[] = $row["followeeid"];
                }
            }

            return $following;
        }

        function getUserByUsername($username, $includeFollowers = true, $includeFollowing = true)
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

                    if($includeFollowers)
                    {
                        $user->followers = $this->getUserFollowers($user->userid);
                    }

                    if($includeFollowing)
                    {
                        $user->following = $this->getUserFollowing($user->userid);
                    }
                }
            }

            return $user;
        }

        function getUserByEmailAndPassword($email, $password, $includeFollowers = true, $includeFollowing = true)
        {
            $user = null;
           
            if($this->databaseConnection != null)
            {
                $tmpUser = $this->getUserByEmail($email, $includeFollowers, $includeFollowing);

                if(password_verify($password, $tmpUser->password))
                {
                    $user = $tmpUser;
                }
            }

            return $user;
        }

        //Returns a user that matches a certain username (ignoring case) and password, or null on error.
        function getUserByUsernameAndPassword($username, $password, $includeFollowers = true, $includeFollowing = true)
        {
            $user = null;

            if($this->databaseConnection != null)
            {
                $tmpUser = $this->getUserByUsername($username, $includeFollowers, $includeFollowing);

                if(password_verify($password, $tmpUser->password))
                {
                    $user = $tmpUser;
                }
            }

            return $user;
        }
    }
?>