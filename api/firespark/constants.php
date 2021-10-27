<?php
    define("TOKEN_ISSUER", "Firespark");
    define("TOKEN_AUDIENCE", "Firespark users");
    define("TOKEN_KEY", "Make*Sure-This)Is}A%VERY+Strong#Password!OR.bad.things/will:happen");
    define("THIRTY_DAYS", 2592000);

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

    define("ERR_BAD_REQUEST", "Bad request.");
    define("ERR_MISSING_REQUIRED_KEYS", "Missing required keys.");
    define("ERR_CANNOT_USE_EMAIL", "Invalid email, or it is already in use.");
    define("ERR_CANNOT_USE_PASSWORD", "Try a different password.");
    define("ERR_CANNOT_USE_USERNAME", "Invalid username, or it is already taken.");
    define("ERR_CANNOT_USE_FIRSTLASTNAME", "Try a different first and last name.");
    define("ERR_CREATE_USER", "Could not register user. Try again later.");

    define("SUCCESS_CREATE_USER", "User has been successfully registered.");

    define("ERR_LOGIN_FAILURE", "Incorrect email or password.");
?>