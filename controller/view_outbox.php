<?php
//view_outbox.php [Display the message outbox of the respective user with pagination.]
//View_outbox_controller

class View_outbox_controller {

function __construct($dbh) {
$this->dbh = $dbh;
}

public function index() {

if (! Auth::is_logged_in()) {
header('Location: index.php?section=login');
exit();
} else {
$user_id_outbox = Auth::get_user_id();
}

require_once 'themes/default_template/header.php';

//Specify how many records should be displayed on each page.

$entries_per_page = 5;

//Specify how many records should be displayed on each page.

try {
$mailbox = new Mailbox($this->dbh);
$mailbox->set_user_id($user_id_outbox);
$total_records = $mailbox->get_number_of_outbox_messages_by_user();

//Pagination.

$current_page = isset($_GET['page']) ? (INT) $_GET['page'] : 1;
$pagination = new Pagination($entries_per_page, $current_page, $total_records);
$pagination->is_valid_page_number();
$offset = $pagination->get_offset();

//Pagination.

$mailbox->set_entries_per_page($entries_per_page);
$mailbox->set_offset($offset);
$rows = $mailbox->get_all_outbox_messages_by_user();
if ($rows === false) {
$message = new SystemMessage('There are currently no messages in your outbox.', 'msg_wrapper_mb');
echo $message->render_message();
} else {
include 'templates/frontend_templates/view_outbox_template.php';
}
} catch (Exception $e) {
include 'templates/messages/message.php';
}

require_once 'themes/default_template/footer.php';

}

}
