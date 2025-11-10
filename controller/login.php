<?php
//login.php [Login the respective user.]
//Login_controller

class Login_controller {

function __construct($dbh) {
$this->dbh = $dbh;
}

public function index() {
if (Auth::is_logged_in()) {
header('Location: index.php');
exit();
}

$csrf_token = new CsrfToken($_SESSION);

require_once 'themes/default_template/header.php';

if (isset($_POST['csrf_token'])) {
if (isset($_POST['login'])) {
if ($csrf_token->validate_token('login', $_POST['csrf_token'])) {

$username_form = '';
if (isset($_POST['username_form'])) {
$username_form = trim($_POST['username_form']);
}

$user_password_form = '';
if (isset($_POST['user_password_form'])) {
$user_password_form = trim($_POST['user_password_form']);
}

try {
$login = new User($this->dbh);
$login->set_username($username_form);
$login->set_user_password($user_password_form);

if ($login->login()) {
$message = new SystemMessage('You have been successfully logged in.', 'msg_wrapper_mt', 'index.php?section=home', 'Back to homepage');
echo $message->render_message();
require_once 'themes/default_template/footer.php';
exit();
}
} catch (Exception $e) {
include 'templates/messages/message_login.php';
require_once 'themes/default_template/footer.php';
exit();
}
} else {
$message = new Message('The session has expired for security reasons.', 'msg_wrapper_mt', 'index.php?section=login', 'Return to login');
echo $message->render_message();
require_once 'themes/default_template/footer.php';
exit();
}
}
}

require_once 'templates/frontend_templates/login_form_template.php';

require_once 'themes/default_template/footer.php';
}

}
