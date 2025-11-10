<?php

//User.php [Class to manage users.]
//pathologicalplay [MMXXV]

class User {

private $dbh;
private $username;
private $user_password;
private $user_password_confirm;
private $user_email;
private $security_question_answer;
private $user_id;
private $user_level;
private $entries_per_page;
private $offset;
private $error_container;

function __construct(Dbh $dbh) {
$this->dbh = $dbh;
}

//Set methods.

public function set_username($username) {
$this->username = $username;
}

public function set_user_password($user_password) {
$this->user_password = $user_password;
}

public function set_user_password_confirm($user_password_confirm) {
$this->user_password_confirm = $user_password_confirm;
}

public function set_user_email($user_email) {
$this->user_email = $user_email;
}

public function set_security_question_answer($security_question_answer) {
$this->security_question_answer = $security_question_answer;
}

public function set_user_id($user_id) {
$this->user_id = $user_id;
}

public function set_user_level($user_level) {
$this->user_level = $user_level;
}

public function set_entries_per_page($entries_per_page) {
$this->entries_per_page = $entries_per_page;
}

public function set_offset($offset) {
$this->offset = $offset;
}

public function set_error_container(ErrorContainer $error_container) {
$this->error_container = $error_container;
}

//Set methods.

//Check if a user exists.

public function validate_id_of_user() {
$sql = "SELECT COUNT(*) AS user_count FROM users WHERE user_id = ?";
$result = $this->dbh->check_if_id_exists($sql, "i", $this->user_id);
if ($result > 0) {
return true;
} else {
return false;
}
}

//Check if a user exists.

//Check if the form is empty.

private function if_login_form_is_empty() {
if (empty($this->username) or empty($this->user_password)) {
return true;
} else {
return false;
}
}

//Check if the form is empty.

//Login.

private function login_db() {
$sql = "SELECT user_id, username, user_password, user_level FROM users WHERE username = ? LIMIT 1";
$result = $this->dbh->get_data($sql, "s", $this->username);
if ($result !== false) {
return $result;
} else {
return false;
}
}

public function login() {

if ($this->if_login_form_is_empty() === true) {
throw new Exception("Please fill out the form completely.");
}

$user = $this->login_db();
if ($user === false) {
throw new Exception("The login was not successful. Please try again.");
}

$user_password = $user['user_password'];
$username = $user['username'];
$user_level = $user['user_level'];
$user_id = $user['user_id'];

if (password_verify($this->user_password, $user_password)) {
$_SESSION['logged_in']['username'] = $username;
$_SESSION['logged_in']['user_level'] = $user_level;
$_SESSION['logged_in']['user_id'] = $user_id;
return true;
} else {
unset($_SESSION['logged_in']['username']);
unset($_SESSION['logged_in']['user_level']);
unset($_SESSION['logged_in']['user_id']);
throw new Exception("The login was not successful. Please try again.");
}
}

//Login.

//Validation of registration data.

private function validate_registration_data() {
if (! empty($this->username) and strlen($this->username) > 30) {
$this->error_container->add_error('The username cannot be longer than 30 characters.');
}

if (! empty($this->username) and strlen($this->username) < 5) {
$this->error_container->add_error('The username cannot be shorter than 5 characters.');
}

if (! empty($this->username) and ! ctype_alnum($this->username)) {
$this->error_container->add_error('The username must consist only of letters and numbers. Special characters are not allowed.');
}

if (! empty($this->user_password) and strlen($this->user_password) > 30) {
$this->error_container->add_error('The password cannot be longer than 30 characters.');
}

if (! empty($this->user_password) and strlen($this->user_password) < 8) {
$this->error_container->add_error('The password cannot be shorter than 8 characters.');
}

if ($this->user_password != $this->user_password_confirm) {
$this->error_container->add_error('The passwords entered do not match.');
}

if (! empty($this->user_email) and ! filter_var($this->user_email, FILTER_VALIDATE_EMAIL)) {
$this->error_container->add_error('The email address is invalid.');
}

if (! empty($this->security_question_answer) and $this->security_question_answer != "old") {
$this->error_container->add_error('The security question was not answered correctly.');
}

if (empty($this->username) or empty($this->user_password) or empty($this->user_password_confirm) or empty($this->user_email) or empty($this->security_question_answer)) {
$this->error_container->add_error('The form must be filled out completely.');
}
}

//Validation of registration data.

//Method to hash the password for registration.

private function hash_password() {
$options = ['cost' => 12];
return password_hash($this->user_password, PASSWORD_BCRYPT, $options);
}

//Method to hash the password for registration.

//Database access for registration.

private function register_db() {
$hashed_user_password = $this->hash_password();
$sql = "INSERT INTO users(username, user_password, user_email, user_date) VALUES(?, ?, ?, NOW())";
$result = $this->dbh->insert_data($sql, "sss", $this->username, $hashed_user_password, $this->user_email);
if ($result !== false) {
return true;
} else {
throw new Exception("A critical error occurred during registration.");
}
}

//Database access for registration.

//Registration.

public function register() {
$this->validate_registration_data();
if ($this->error_container->has_errors()) {
return false;
} else {
try {
$this->register_db();
return true;
} catch (mysqli_sql_exception $e) {
if ($e->getCode() == 1062) {
throw new Exception("The username or email address is already taken.");
}
}
}
}

//Registration.

//Total number of users for pagination.

private function get_number_of_users_db() {
$sql = "SELECT user_id, username, user_date, user_email, user_level FROM users";
$result = $this->dbh->count_entire_data_from_table($sql);
return $result;
}

public function get_number_of_users() {
$result = $this->get_number_of_users_db();
if ($result !== false) {
return $result;
} else {
return false;
}
}

//Total number of users for pagination.

//Output of all users for the backend.

private function get_all_users_db() {
$offset = $this->offset;
$entries_per_page = $this->entries_per_page;
$user_level = 'administrator';
$sql = "SELECT user_id, username, user_date, user_email, user_level, last_activity, TIMESTAMPDIFF(MINUTE,last_activity,NOW()) AS last_activity_minutes FROM users WHERE user_level != ? ORDER BY user_date DESC LIMIT $offset, $entries_per_page";
return $result = $this->dbh->get_all_data_by_value($sql, "s", $user_level);
}

public function get_all_users() {
$result = $this->get_all_users_db();
if ($result !== false) {
return $result;
} else {
return false;
}
}

//Output of all users for the backend.

//Remove a user.

private function is_admin() {
$sql = "SELECT user_level FROM users WHERE user_id = ?";
$result = $this->dbh->get_single_value($sql, "i", $this->user_id);
if ($result === 'administrator') {
return true;
} else {
return false;
}
}

private function remove_user_db() {
$sql = "DELETE FROM users WHERE user_id = ? LIMIT 1";
$result = $this->dbh->remove_data($sql, "i", $this->user_id);
if ($result) {
return true;
} else {
return false;
}
}

public function remove_user() {
if (Auth::is_administrator()) {
if ($this->is_admin() === true) {
throw new Exception("An administrator cannot be removed directly.");
}
if ($this->remove_user_db()) {
return true;
} else {
throw new Exception("A critical error occurred while deleting the user.");
}
} else {
throw new Exception("You are not authorized to perform this action.");
}
}

//Remove a user.

//Update user level.

private function update_user_level_db() {
$sql = "UPDATE users SET user_level = ? WHERE user_id = ? LIMIT 1";
$result = $this->dbh->update_data($sql, "si", $this->user_level, $this->user_id);
if ($result) {
return true;
} else {
return false;
}
}

public function update_user_level() {
if (Auth::is_administrator()) {
if ($this->is_admin() === true) {
throw new Exception("The user level of an administrator cannot be changed directly.");
}
if ($this->update_user_level_db()) {
return true;
} else {
throw new Exception("A critical error occurred while deleting the user.");
}
} else {
throw new Exception("You are not authorized to perform this action.");
}
}

//Update user level.

//Update the timestamp of the user's last activity.

private function update_last_activity_db() {
$sql = "UPDATE users SET last_activity = NOW() WHERE user_id = ? LIMIT 1";
$result = $this->dbh->update_data($sql, "i", $this->user_id);
if ($result) {
return true;
} else {
return false;
}
}

public function update_last_activity() {
if (Auth::is_logged_in()) {
if ($this->update_last_activity_db()) {
return true;
} else {
throw new Exception("A critical error has occurred.");
}
} else {
throw new Exception("You are not authorized to perform this action.");
}
}

//Update the timestamp of the user's last activity.

}
