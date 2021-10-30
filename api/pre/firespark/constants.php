<?php
    define("HS512", "HS512");
    define("TOKEN_ISSUER", "Firespark");
    define("TOKEN_AUDIENCE", "Firespark users");
    define("TOKEN_KEY", "Make*Sure-This)Is}A%VERY+Strong#Password!OR.bad.things/will:happen");
    define("THIRTY_DAYS", 2592000); //In seconds.

    define("DB_NAME", "firespark");
    define("DB_NAMEPRE", "firesparkpre");
    define("DB_HOST", "localhost");
    define("DB_USER", "fsdbguy");
    define("DB_PASSWORD", "Kp6SS1W6imawhHKt");

    define("KEY_EMAIL", "email");
    define("KEY_PASSWORD", "password");
    define("KEY_USERNAME", "username");
    define("KEY_FIRSTLASTNAME", "firstlastname");
    define("KEY_EMAIL_OR_USERNAME", "email_or_username");
    define("KEY_USER_ID", "userid");
    define("KEY_ERRNO", "errno");
    define("KEY_ACTION", "action");
    define("KEY_ACTION_LIKE", "like");
    define("KEY_ACTION_UNLIKE", "unlike");
    define("KEY_FOLLOWEE_ID", "followeeid");
    define("KEY_ACTION_FOLLOW", "follow");
    define("KEY_ACTION_UNFOLLOW", "unfollow");

    define("ERR_BAD_REQUEST", "Bad request.");
    define("ERR_MISSING_REQUIRED_KEYS", "Missing required keys.");
    define("ERR_CANNOT_USE_EMAIL", "Invalid email, or it is already in use.");
    define("ERR_CANNOT_USE_PASSWORD", "Try a different password.");
    define("ERR_CANNOT_USE_USERNAME", "Invalid username, or it is already taken.");
    define("ERR_CANNOT_USE_FIRSTLASTNAME", "Try a different first and last name.");
    define("ERR_CREATE_USER", "Could not register user. Try again.");
    define("ERR_INVALID_CREDENTIALS", "Invalid credentials detected.");
    define("ERRNO_INVALID_CREDENTIALS", "ERRNO_IC");
    define("ERR_SEND_SPARK", "Could not send spark. Try again.");
    define("ERR_DELETE_SPARK", "The Spark could not be deleted.");
    define("ERR_LIKE_SPARK", "The Spark could not be liked.");
    define("ERR_UNLIKE_SPARK", "The Spark could not be unliked.");
    define("ERR_CANNOT_PERFORM_ACTION", "Could not perform this action.");
    define("ERR_FOLLOW_USER", "The User could not be followed.");
    define("ERR_UNFOLLOW_USER", "The User could not be unfollowed.");

    define("SUCCESS_CREATE_USER", "User has been successfully registered.");
    define("SUCCESS_DELETE_SPARK", "The Spark has been deleted.");
    define("SUCCESS_LIKE_SPARK", "The Spark has been liked.");
    define("SUCCESS_UNLIKE_SPARK", "The Spark has been unliked.");
    define("SUCCESS_FOLLOW_USER", "The User has been followed.");
    define("SUCCESS_UNFOLLOW_USER", "The User has been unfollowed.");

    define("ERR_LOGIN_FAILURE", "Incorrect email or password.");

    define("KEY_SPARK_ID", "sparkid");
    define("KEY_SPARK_BODY", "body");
    define("KEY_SPARK_CREATED", "created");
    define("KEY_TOKEN_AUTH", "Authorization");
    define("KEY_TOKEN_USER_DATA", "userdata");

    define("MAX_SPARK_BODY_LENGTH", 150);
?>