<?php

//Mailbox.php [Class to process the mailboxes of the respective user.]
//pathologicalplay [MMXXV]

class Mailbox {

private $dbh;
private $user_id;
private $outbox = "out";
private $inbox = "in";
private $message_token;
private $entries_per_page;
private $offset;
private $message_status_read = 1;
private $message_status_unread = 0;

function __construct(Dbh $dbh) {
$this->dbh = $dbh;
}

//Set methods.

public function set_user_id($user_id) {
$this->user_id = $user_id;
}

public function set_mailbox_type($mailbox_type) {
$this->mailbox_type = $mailbox_type;
}

public function set_message_token($message_token) {
$this->message_token = $message_token;
}

public function set_entries_per_page($entries_per_page) {
$this->entries_per_page = $entries_per_page;
}

public function set_offset($offset) {
$this->offset = $offset;
}


//Set methods.

//Total number of messages for pagination.

private function get_number_of_inbox_messages_by_user_db() {
$sql = "SELECT COUNT(*) AS count FROM user_mailboxes WHERE user_id = ? AND mailbox = ?";
$result = $this->dbh->get_data($sql, "is", $this->user_id, $this->inbox);
return $result['count'];
}

public function get_number_of_inbox_messages_by_user() {
$result = $this->get_number_of_inbox_messages_by_user_db();
if ($result !== false) {
return $result;
} else {
return false;
}
}

private function get_number_of_outbox_messages_by_user_db() {
$sql = "SELECT COUNT(*) AS count FROM user_mailboxes WHERE user_id = ? AND mailbox = ?";
$result = $this->dbh->get_data($sql, "is", $this->user_id, $this->outbox);
return $result['count'];
}

public function get_number_of_outbox_messages_by_user() {
$result = $this->get_number_of_outbox_messages_by_user_db();
if ($result !== false) {
return $result;
} else {
return false;
}
}

//Total number of messages for pagination.

//Output of all inbox messages by user.

private function get_all_inbox_messages_by_user_db() {
$offset = $this->offset;
$entries_per_page = $this->entries_per_page;
$sql = "SELECT mailbox.message_id, mailbox.message, mailbox.message_title, mailbox.sent_by_id, mailbox.message_token, mailbox.message_created, user_mailboxes.message_status, users.user_id, users.username AS sender_username FROM user_mailboxes LEFT JOIN mailbox ON mailbox.message_id = user_mailboxes.message_id LEFT JOIN users ON mailbox.sent_by_id = users.user_id WHERE mailbox.sent_to_id = ? AND user_mailboxes.mailbox = ? ORDER BY mailbox.message_created DESC LIMIT $offset, $entries_per_page";
return $result = $this->dbh->get_all_data_by_value($sql, "is", $this->user_id, $this->inbox);
}

public function get_all_inbox_messages_by_user() {
if (Auth::is_logged_in()) {
$result = $this->get_all_inbox_messages_by_user_db();
if ($result !== false) {
return $result;
} else {
return false;
}
} else {
throw new Exception("You are not authorized to perform this action.");
}
}

//Output of all inbox messages by user.

//Output of all outbox messages by user.

private function get_all_outbox_messages_by_user_db() {
$offset = $this->offset;
$entries_per_page = $this->entries_per_page;
$sql = "SELECT mailbox.message_id, mailbox.message, mailbox.message_title, mailbox.sent_by_id, mailbox.message_token, mailbox.message_created, user_mailboxes.message_status, users.user_id, users.username AS receiver_username FROM user_mailboxes LEFT JOIN mailbox ON mailbox.message_id = user_mailboxes.message_id LEFT JOIN users ON mailbox.sent_to_id = users.user_id WHERE mailbox.sent_by_id = ? AND user_mailboxes.mailbox = ? ORDER BY mailbox.message_created DESC LIMIT $offset, $entries_per_page";
return $result = $this->dbh->get_all_data_by_value($sql, "is", $this->user_id, $this->outbox);
}

public function get_all_outbox_messages_by_user() {
if (Auth::is_logged_in()) {
$result = $this->get_all_outbox_messages_by_user_db();
if ($result !== false) {
return $result;
} else {
return false;
}
} else {
throw new Exception("You are not authorized to perform this action.");
}
}

//Output of all outbox messages by user.

//Check if the user has permission to request the inbox message.

public function check_inbox_authorization() {
if (empty($this->message_token) OR ! isset($this->message_token)) {
throw new Exception("The entered token is not valid.");
} else {
$sql = "SELECT user_mailboxes.user_id, mailbox.message_token FROM user_mailboxes LEFT JOIN mailbox ON user_mailboxes.message_id = mailbox.message_id WHERE mailbox.message_token = ? AND user_mailboxes.mailbox = ? LIMIT 1";
$result = $this->dbh->get_data($sql, "ss", $this->message_token, $this->inbox);
if ($result === false) {
throw new Exception("The token could not be associated with any message.");
} else {
$token_from_db = $result['message_token'];
$user_id_from_db = (INT) $result['user_id'];
if ($token_from_db === $this->message_token && is_int($user_id_from_db) && $user_id_from_db === $this->user_id) {
return true;
} else {
return false;
}
}
}
}

//Check if the user has permission to request the inbox message.

//Output of a inbox message.

private function show_inbox_message_db() {
$sql = "SELECT mailbox.message, mailbox.message_title, mailbox.message_created, mailbox.sent_by_id AS sender_id, users.username AS sender_username FROM user_mailboxes LEFT JOIN mailbox ON user_mailboxes.message_id = mailbox.message_id LEFT JOIN users ON mailbox.sent_by_id = users.user_id WHERE mailbox.message_token = ? AND user_mailboxes.mailbox = ?";
return $result = $this->dbh->get_data($sql, "ss", $this->message_token, $this->inbox);
}

public function show_inbox_message() {
if ($this->check_inbox_authorization() === false) {
throw new Exception("You are not authorized to access this message.");
} else {
$result = $this->show_inbox_message_db();
if ($result !== false) {
return $result;
} else {
throw new Exception("An error occurred while loading the message.");
}
}
}

//Output of a inbox message.

//Check if the user has permission to request the outbox message.

public function check_outbox_authorization() {
if (empty($this->message_token) OR ! isset($this->message_token)) {
throw new Exception("The entered token is not valid.");
} else {
$sql = "SELECT user_mailboxes.user_id, mailbox.message_token FROM user_mailboxes LEFT JOIN mailbox ON user_mailboxes.message_id = mailbox.message_id WHERE mailbox.message_token = ? AND user_mailboxes.mailbox = ? LIMIT 1";
$result = $this->dbh->get_data($sql, "ss", $this->message_token, $this->outbox);
if ($result === false) {
throw new Exception("The token could not be associated with any message.");
} else {
$token_from_db = $result['message_token'];
$user_id_from_db = (INT) $result['user_id'];
if ($token_from_db === $this->message_token && is_int($user_id_from_db) && $user_id_from_db === $this->user_id) {
return true;
} else {
return false;
}
}
}
}

//Check if the user has permission to request the outbox message.

//Output of a outbox message.

private function show_outbox_message_db() {
$sql = "SELECT mailbox.message, mailbox.message_title, mailbox.message_created, mailbox.sent_by_id AS sender_id, users.username AS sender_username FROM user_mailboxes LEFT JOIN mailbox ON user_mailboxes.message_id = mailbox.message_id LEFT JOIN users ON mailbox.sent_by_id = users.user_id WHERE mailbox.message_token = ? AND user_mailboxes.mailbox = ?";
return $result = $this->dbh->get_data($sql, "ss", $this->message_token, $this->outbox);
}

public function show_outbox_message() {
if ($this->check_outbox_authorization() === false) {
throw new Exception("You are not authorized to access this message.");
} else {
$result = $this->show_outbox_message_db();
if ($result !== false) {
return $result;
} else {
throw new Exception("An error occurred while loading the message.");
}
}
}

//Output of a outbox message.

//Update message status.

private function update_message_status_db() {
$sql_1 = "SELECT message_id FROM mailbox WHERE message_token = ?";
$message_id_db = $this->dbh->get_single_value($sql_1, "s", $this->message_token);
if ($message_id_db !== false && ! empty($message_id_db)) {
$sql_2 = "UPDATE user_mailboxes SET message_status = ? WHERE user_id = ? AND message_id = ? LIMIT 1";
$result_2 = $this->dbh->update_data($sql_2, "iii", $this->message_status_read, $this->user_id, $message_id_db);
return true;
} else {
return false;
}
}

public function update_message_status() {
if (Auth::is_logged_in()) {
$result = $this->update_message_status_db();
if ($result !== false) {
return $result;
} else {
throw new Exception("An error occurred while updating the message status.");
}
} else {
throw new Exception("You are not authorized to perform this action.");
}
}

//Update message status.

//Check whether a message has been read.

private function check_message_status_db() {
$sql = "SELECT COUNT(message_id) as inbox_status FROM user_mailboxes WHERE user_id = ? AND message_status = ? AND mailbox = ?";
$result = $this->dbh->get_data($sql, "iis", $this->user_id, $this->message_status_unread, $this->inbox);
return $result['inbox_status'];
}

public function check_message_status() {
$result = $this->check_message_status_db();
if ($result !== false) {
return $result;
} else {
return false;
}
}

//Check whether a message has been read.

}
