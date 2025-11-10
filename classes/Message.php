<?php

//Message.php [Class for managing messages.]
//pathologicalplay [MMXXV]

class Message {

private $dbh;
private $sent_to_id;
private $sent_by_id;
private $message_text;
private $message_title;
private $outbox = "out";
private $inbox = "in";
private $error_container;

function __construct(Dbh $dbh) {
$this->dbh = $dbh;
}

//Set methods.

public function set_sent_by_id($sent_by_id) {
$this->sent_by_id = $sent_by_id;
}

public function set_message_title($message_title) {
$this->message_title = $message_title;
}

public function set_message_text($message_text) {
$this->message_text = $message_text;
}

public function set_error_container(ErrorContainer $error_container) {
$this->error_container = $error_container;
}

//Set methods.

//Get the user's ID based on the username entered.

public function set_sent_to_username($username) {
if (empty($username)) {
$this->error_container->add_error('Username required.');
} else {
$sql = "SELECT user_id FROM users WHERE username = ?";
$result = $this->dbh->get_single_value($sql, "s", $username);
if ($result !== false && ! empty($result)) {
$this->sent_to_id = $result;
} else {
$this->error_container->add_error('The user does not exist.');
}
}
}

//Get the user's ID based on the username entered.

//Send a message by message Token.

public function set_sent_to_message_token($message_token) {
if (empty($message_token)) {
$this->error_container->add_error('A message token is required.');
} else {
$sql = "SELECT sent_by_id, message_title FROM mailbox WHERE message_token = ?";
$result = $this->dbh->get_data($sql, "s", $message_token);
if ($result !== false) {
$sent_by_id_db = (INT) $result['sent_by_id'];
$message_title_db = $result['message_title'];
$this->sent_to_id = $sent_by_id_db;
$this->set_message_title($message_title_db);
} else {
$this->error_container->add_error('The message you are trying to reply to does not exist.');
}
}
}

//Send a message by message Token.

//Validation of a message.

private function validate_message() {

if ($this->sent_to_id === $this->sent_by_id) {
$this->error_container->add_error('You cannot send yourself a message.');
}

if (empty($this->message_title) OR empty($this->message_text)) {
$this->error_container->add_error('The form must be filled out completely.');
}

}

//Validation of a message.

//Saving a message.

private function save_message_db() {

$message_token = bin2hex(random_bytes(32));

$sql_1 =  "INSERT INTO mailbox(message, message_title, sent_by_id, sent_to_id, message_token, message_created) VALUES(?, ?, ?, ?, ?, NOW())";
$result_1 = $this->dbh->insert_data_and_return($sql_1, "ssiis", $this->message_text, $this->message_title, $this->sent_by_id, $this->sent_to_id, $message_token);
$new_message_id = $result_1->insert_id;

$sql_2 = "INSERT INTO user_mailboxes(user_id, mailbox, message_id) VALUES(?, ?, ?)";
$result_2 = $this->dbh->insert_data($sql_2, "isi", $this->sent_by_id, $this->outbox, $new_message_id);

$sql_3 =  "INSERT INTO user_mailboxes(user_id, mailbox, message_id) VALUES(?, ?, ?)";
$result_3 = $this->dbh->insert_data($sql_3, "isi", $this->sent_to_id, $this->inbox, $new_message_id);

return array('result_1' => $new_message_id, 'result_2' => $result_2, 'result_3' => $result_3);
}

public function save_message() {
if (Auth::is_logged_in()) {
$this->validate_message();
if ($this->error_container->has_errors()) {
return false;
} else {
$result = $this->save_message_db();
if ($result['result_1'] !== false && $result['result_2'] !== false && $result['result_3'] !== false) {
return true;
} else {
throw new Exception("A critical error occurred while saving the message.");
}
}
} else {
throw new Exception("You are not authorized to perform this action.");
}
}

//Saving a message.

}
