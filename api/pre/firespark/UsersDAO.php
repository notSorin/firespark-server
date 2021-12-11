<?php
    require_once('DatabaseOperation.php');
    require_once('constants.php');
    require_once('User.php');

    //Data Access Object which contains functions for manipulating Users on the database.
    class UsersDAO extends DatabaseOperation
    {
        function __construct()
        {
            parent::__construct();
        }

        //Returns a user by email, or null if the user does not exist.
        function getUserByEmail($email, $includeFollowers = true, $includeFollowing = true)
        {
            $user = null;
           
            if($this->databaseConnection !== null)
            {
                $sql = "select *
                        from users
                        where email = ?;";
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

        //Returns an array with the ids of users who follow a certain user, or an empty
        //array if no users follow them, or null on error.
        function getUserFollowers($userId)
        {
            $followers = null;
           
            if($this->databaseConnection !== null)
            {
                $sql = "select userid
                        from followers
                        where followeeid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $userId);
                $statement->execute();

                $result = $statement->get_result();
                $followers = [];

                while($row = mysqli_fetch_assoc($result))
                {
                    $followers[] = $row["userid"];
                }
            }

            return $followers;
        }

        //Returns an array with the ids of users whom a user is following, or an empty
        //array if the user does not follow anyone, or null on error.
        function getUserFollowing($userId)
        {
            $following = null;
           
            if($this->databaseConnection !== null)
            {
                $sql = "select followeeid
                        from followers
                        where userid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $userId);
                $statement->execute();

                $result = $statement->get_result();
                $following = [];

                while($row = mysqli_fetch_assoc($result))
                {
                    $following[] = $row["followeeid"];
                }
            }

            return $following;
        }

        //Returns a user by their username, or null if the user does not exist.
        function getUserByUsername($username, $includeFollowers = true, $includeFollowing = true)
        {
            $user = null;
           
            if($this->databaseConnection !== null)
            {
                $sql = "select *
                        from users
                        where username = ?;";
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

        //Returns a user by their email and password, or null if the password does not match the user or if an
        //error occurs.
        function getUserByEmailAndPassword($email, $password, $includeFollowers = true, $includeFollowing = true)
        {
            $user = null;
           
            if($this->databaseConnection !== null)
            {
                $tmpUser = $this->getUserByEmail($email, $includeFollowers, $includeFollowing);

                if(password_verify($password, $tmpUser->password))
                {
                    $user = $tmpUser;
                }
            }

            return $user;
        }

        //Returns a user by their username (ignoring case) and password, or null if the password does not match
        //the user or if an error occurs.
        function getUserByUsernameAndPassword($username, $password, $includeFollowers = true, $includeFollowing = true)
        {
            $user = null;

            if($this->databaseConnection !== null)
            {
                $tmpUser = $this->getUserByUsername($username, $includeFollowers, $includeFollowing);

                if($tmpUser !== null)
                {
                    if(password_verify($password, $tmpUser->password))
                    {
                        $user = $tmpUser;
                    }
                }
            }

            return $user;
        }

        //Inserts a new user into the database. Returns true on success, false otherwise.
        function insertUser($email, $password, $username, $firstlastname)
        {
            $success = false;

            if($this->databaseConnection !== null)
            {
                $sql = "insert into users (email, password, username, firstlastname)
                        values (?, ?, ?, ?);";
                $statement = $this->databaseConnection->prepare($sql);
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $statement->bind_param("ssss", $email, $passwordHash, $username, $firstlastname);
                
                $success = $statement->execute();
            }

            return $success;
        }

        //Returns true if an email is already in use by someone, false otherwise.
        function isEmailUsed($email)
        {
            $isUsed = false;

            if($this->databaseConnection !== null)
            {
                $sql = "select *
                        from users
                        where email = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("s", $email);
                $statement->execute();

                $rowsFound = $statement->get_result()->num_rows;
                $isUsed = $rowsFound != 0;
            }

            return $isUsed;
        }

        //Returns true if a username is already in user by someone, false otherwise.
        function isUsernameUsed($username)
        {
            $isUsed = false;

            if($this->databaseConnection !== null)
            {
                $sql = "select *
                        from users
                        where username = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("s", $username);
                $statement->execute();

                $rowsFound = $statement->get_result()->num_rows;
                $isUsed = $rowsFound != 0;
            }

            return $isUsed;
        }

        //Returns true if the user with $followeeid is followed by the user with $userid,
        //false otherwise.
        function isUserFollowed($followeeid, $userid)
        {
            $isFollowed = false;

            if($followeeid != $userid) //A user cannot follow themselves.
            {
                if($this->databaseConnection !== null)
                {
                    $sql = "select *
                            from followers
                            where userid = ? and followeeid = ?;";
                    $statement = $this->databaseConnection->prepare($sql);

                    $statement->bind_param("ii", $userid, $followeeid);
                    
                    if($statement->execute())
                    {
                        $result = $statement->get_result();
                        $isFollowed = $result->num_rows == 1;
                    }
                }
            }

            return $isFollowed;
        }

        //Adds a new entry into the followers table indicating that the user with $userid follows
        //the user with $followeeid. Returns true on success, false otherwise.
        function followUser($followeeid, $userid)
        {
            $success = false;

            if($followeeid != $userid) //A user cannot follow themselves.
            {
                //Consider success if the followee is already followed by the user.
                $success = $this->isUserFollowed($followeeid, $userid);

                if(!$success)
                {
                    $sql = "insert into followers (userid, followeeid)
                            values (?, ?);";
                    $statement = $this->databaseConnection->prepare($sql);

                    $statement->bind_param("ii", $userid, $followeeid);

                    if($statement->execute())
                    {
                        $success = $statement->affected_rows == 1;
                    }
                }
            }

            return $success;
        }

        //Removes the entry from the followers table where the user with $userid follows
        //the user with $followeeid. Returns true on success, false otherwise.
        function unfollowUser($followeeid, $userid)
        {
            $success = false;

            //A user cannot unfollow themselves because they should have not been able
            //to follow themselves in the first place.
            if($followeeid != $userid)
            {
                //Consider success if the followee is not followed by the user.
                $success = !$this->isUserFollowed($followeeid, $userid);

                if(!$success)
                {
                    $sql = "delete from followers
                            where userid = ? and followeeid = ?;";
                    $statement = $this->databaseConnection->prepare($sql);

                    $statement->bind_param("ii", $userid, $followeeid);

                    if($statement->execute())
                    {
                        $success = $statement->affected_rows == 1;
                    }
                }
            }

            return $success;
        }

        //Returns a user by their id, or null if the user does not exist or an error occurs.
        function getUserById($userId, $includeFollowers = true, $includeFollowing = true)
        {
            $user = null;
           
            if($this->databaseConnection !== null)
            {
                $sql = "select *
                        from users
                        where userid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $userId);   
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

        //Returns an array of user profiles whose usernames or first and last names match the parameter name,
        //or an empty array if no users match the given name or if an error occurs.
        function getUsersByName($name, $includeFollowers = true, $includeFollowing = true)
        {
            $users = null;
            $sql = "select *
                    from users
                    where username like ? or firstlastname like ?;";
            $statement = $this->databaseConnection->prepare($sql);
            $param = "{$name}%";

            $statement->bind_param("ss", $param, $param);
            
            if($statement->execute())
            {
                $users = [];
                $result = $statement->get_result();
                
                while($user = $result->fetch_object("User"))
                {
                    if($includeFollowers)
                    {
                        $user->followers = $this->getUserFollowers($user->userid);
                    }

                    if($includeFollowing)
                    {
                        $user->following = $this->getUserFollowing($user->userid);
                    }

                    $users[] = $user;
                }
            }

            return $users;
        }
    }
?>