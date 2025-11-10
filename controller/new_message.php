<?php
//new_message.php [Sending a message.]
//New_message_controller

class New_message_controller {

function __construct($dbh) {
$this->dbh = $dbh;
}

public function index() {

if (! Auth::is_logged_in()) {
header('Location: index.php?section=login');
exit();
}


$csrf_token = new CsrfToken($_SESSION);

$error_container = new ErrorContainer();

require_once 'themes/default_template/header.php';

//Sending a message.

if (isset($_POST['csrf_token'])) {
if (isset($_POST['send_message'])) {
if ($csrf_token->validate_token('send_message', $_POST['csrf_token'])) {

$sent_by_id = Auth::get_user_id();

$username_form = '';
if (isset($_POST['username_form'])) {
$username_form = trim($_POST['username_form']);
}

$message_title_form = '';
if (isset($_POST['message_title_form'])) {
$message_title_form = trim($_POST['message_title_form']);
}

$message_text_form = '';
if (isset($_POST['message_text_form'])) {
$message_text_form = trim($_POST['message_text_form']);
}


try {
$message_manager = new Message($this->dbh);
$message_manager->set_error_container($error_container);
$message_manager->set_sent_by_id($sent_by_id);
$message_manager->set_sent_to_username($username_form);
$message_manager->set_message_title($message_title_form);
$message_manager->set_message_text($message_text_form);
if ($message_manager->save_message()) {
$message = new SystemMessage('The message has been sent successfully.', 'msg_wrapper_mt', 'index.php?section=new_message', 'Return to new message');
echo $message->render_message();
require_once 'themes/default_template/footer.php';
exit();
}
} catch (Exception $e) {
include 'templates/messages/message.php';
require_once 'themes/default_template/footer.php';
exit();
}
} else {
$message = new SystemMessage('The session has expired for security reasons.', 'msg_wrapper_mt', 'index.php?section=new_message', 'Return to new message');
echo $message->render_message();
require_once 'themes/default_template/footer.php';
exit();
}
}
}

//Sending a message.

require_once 'templates/frontend_templates/new_message_template.php';
require_once 'themes/default_template/footer.php';

}

}
