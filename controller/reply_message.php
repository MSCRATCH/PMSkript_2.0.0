<?php
//reply_message.php [Reply to a message.]
//Reply_message_controller

class Reply_message_controller {

function __construct($dbh) {
$this->dbh = $dbh;
}

public function index() {

if (! Auth::is_logged_in()) {
header('Location: index.php?section=login');
exit();
} else {
$user_id_inbox = Auth::get_user_id();
}

$csrf_token = new CsrfToken($_SESSION);

$error_container = new ErrorContainer();

require_once 'themes/default_template/header.php';

//Token of the message.

$message_token_get = '';
if (isset($_GET['id'])) {
$message_token_get = trim($_GET['id']);
}

//Token of the message.

//Sending a message.

if (isset($_POST['csrf_token'])) {
if (isset($_POST['reply_message'])) {
if ($csrf_token->validate_token('reply_message', $_POST['csrf_token'])) {

$message_text_form = '';
if (isset($_POST['message_text_form'])) {
$message_text_form = trim($_POST['message_text_form']);
}

try {
$message_manager = new Message($this->dbh);
$message_manager->set_error_container($error_container);
$message_manager->set_sent_by_id($user_id_inbox);
$message_manager->set_sent_to_message_token($message_token_get);
$message_manager->set_message_text($message_text_form);
if ($message_manager->save_message()) {
$message = new SystemMessage('The reply has been sent successfully.', 'msg_wrapper_mt', 'index.php?section=view_inbox', 'Return to inbox');
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

//View the message.

try {
$mailbox = new Mailbox($this->dbh);
$mailbox->set_user_id($user_id_inbox);
$mailbox->set_message_token($message_token_get);
$message = $mailbox->show_inbox_message();
$mailbox->update_message_status();

include 'templates/frontend_templates/reply_message_template.php';

} catch (Exception $e) {
include 'templates/messages/message.php';
}
require_once 'themes/default_template/footer.php';

//View the message.

}

}
