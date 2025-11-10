<?php
//view_message_controller.php [Displaying a message by message_token.]
//View_message_controller

class View_message_controller {

function __construct($dbh) {
$this->dbh = $dbh;
}

public function index() {

if (! Auth::is_logged_in()) {
header('Location: index.php?section=login');
exit();
} else {
$user_message_outbox = Auth::get_user_id();
}

require_once 'themes/default_template/header.php';

//Token of the message.

$message_token_get = '';
if (isset($_GET['id'])) {
$message_token_get = trim($_GET['id']);
}

//Token of the message.


try {
$mailbox = new Mailbox($this->dbh);
$mailbox->set_user_id($user_message_outbox);
$mailbox->set_message_token($message_token_get);

$message = $mailbox->show_outbox_message();


include 'templates/frontend_templates/view_message_template.php';

} catch (Exception $e) {
include 'templates/messages/message.php';
}

require_once 'themes/default_template/footer.php';

}

}
