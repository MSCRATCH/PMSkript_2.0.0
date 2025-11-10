<?php

//Auth.php [User authentication.]
//pathologicalplay [MMXXV]

class Auth {

//Check if a user is logged in.

public static function is_logged_in() {
return isset($_SESSION['logged_in']['user_level']);
}

//Check if a user is logged in.

//Check if a user is an administrator.

public static function is_administrator() {
return isset($_SESSION['logged_in']['user_level']) && $_SESSION['logged_in']['user_level'] == 'administrator';
}

//Check if a user is an administrator.

//Check if a user is an administrator or moderator.

public static function is_administrator_or_moderator() {
return isset($_SESSION['logged_in']['user_level']) && $_SESSION['logged_in']['user_level'] == 'administrator' OR isset($_SESSION['logged_in']['user_level']) && $_SESSION['logged_in']['user_level'] == 'moderator';
}

//Check if a user is an administrator or moderator.

//Check if a user is activated.

public static function is_not_activated() {
return isset($_SESSION['logged_in']['user_level']) && $_SESSION['logged_in']['user_level'] == 'not_activated';
}

//Check if a user is activated.

//Get username from the session.

public static function get_username() {
if (isset($_SESSION['logged_in']['username'])) {
return $_SESSION['logged_in']['username'];
} else {
return false;
}
}

//Get username from the session.

//Get ID from the session.

public static function get_user_id() {
if (isset($_SESSION['logged_in']['user_id'])) {
return (INT) $_SESSION['logged_in']['user_id'];
} else {
return false;
}
}

//Get ID from the session.

//Get user level from the session.

public static function get_user_level() {
if (isset($_SESSION['logged_in']['user_level'])) {
return $_SESSION['logged_in']['user_level'];
} else {
return false;
}
}

//Get user level from the session.

}
