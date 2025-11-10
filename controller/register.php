<?php
//register.php [Registering a new user.]
//Register_controller

class Register_controller {

function __construct($dbh) {
$this->dbh = $dbh;
}

public function index() {

if (Auth::is_logged_in()) {
header('Location: index.php');
}

require_once 'themes/default_template/header.php';

$csrf_token = new CsrfToken($_SESSION);

$error_container = new ErrorContainer();

if (isset($_POST['csrf_token'])) {
if (isset($_POST['register'])) {
if ($csrf_token->validate_token('register', $_POST['csrf_token'])) {

$username_form = '';
if (isset($_POST['username_form'])) {
$username_form = trim($_POST['username_form']);
}

$user_password_form = '';
if (isset($_POST['password_form'])) {
$user_password_form = trim($_POST['password_form']);
}

$user_password_confirm_form = '';
if (isset($_POST['user_password_confirm_form'])) {
$user_password_confirm_form = trim($_POST['user_password_confirm_form']);
}

$user_email_form = '';
if (isset($_POST['user_email_form'])) {
$user_email_form = trim($_POST['user_email_form']);
}

$security_question_answer_form = '';
if (isset($_POST['security_question_answer_form'])) {
$security_question_answer_form = trim($_POST['security_question_answer_form']);
}

try {
$register = new User($this->dbh);
$register->set_error_container($error_container);
$register->set_username($username_form);
$register->set_user_password($user_password_form);
$register->set_user_Password_confirm($user_password_confirm_form);
$register->set_user_email($user_email_form);
$register->set_security_question_answer($security_question_answer_form);

if ($register->register()) {
$message = new SystemMessage('You have been successfully registered.', 'msg_wrapper_mt', 'index.php', 'Back to homepage');
echo $message->render_message();
require_once 'themes/default_template/footer.php';
exit();
}
} catch (Exception $e) {
include 'templates/messages/message_register.php';
require_once 'themes/default_template/footer.php';
exit();
}
} else {
$message = new SystemMessage('The session has expired for security reasons.', 'msg_wrapper_mt', 'index.php?section=register', 'Return to register');
echo $message->render_message();
require_once 'themes/default_template/footer.php';
exit();
}
}
}

require_once 'templates/frontend_templates/register_form_template.php';

require_once 'themes/default_template/footer.php';

}

}
