<?php

//Router.php [Router of the script.]
//pathologicalplay [MMXXV]

class Router {
private $dbh;
private $allowed_sections;

function __construct() {
$this->dbh = new Dbh();
$this->allowed_sections = array(
'home',
'login',
'logout',
'register',
'user_management',
'new_message',
'view_inbox',
'view_outbox',
'view_message',
'reply_message',
);
}

private function update_users_last_activity() {
if (Auth::is_logged_in()) {
$user_front_controller = new User($this->dbh);
$user_id_front_controller = Auth::get_user_id();
$user_front_controller->set_user_id($user_id_front_controller);
$user_front_controller->update_last_activity();
}
}

private function check_if_user_is_activated() {
if (Auth::is_not_activated()) {
require_once 'themes/default_template/header.php';
include 'templates/messages/message_not_activated.php';
require_once 'themes/default_template/footer.php';
exit();
}
}

public function route() {

$this->update_users_last_activity();
$this->check_if_user_is_activated();

$section = $_GET['section'] ?? 'home';
$section = sanitize($section);
if (in_array($section, $this->allowed_sections)) {
$file_name = $section. '.php';
$controller_path = 'controller/'. $section. '.php';
if (file_exists($controller_path)) {
require_once $controller_path;
}
$controller_name = ucfirst($section). '_controller';
$controller = new $controller_name($this->dbh);
$controller->index();
} else {
require_once 'controller/home.php';
$controller = new Home_controller($this->dbh);
$controller->index();
}
}


}
