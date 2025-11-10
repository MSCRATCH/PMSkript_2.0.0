<?php
//user_management.php [User management.]
//User_management_controller

class User_management_controller {

function __construct($dbh) {
$this->dbh = $dbh;
}

public function index() {

if (! Auth::is_administrator()) {
header('Location: index.php?section=login');
exit();
}


$csrf_token = new CsrfToken($_SESSION);

require_once 'themes/backend_template/backend_header.php';

//Update user level.

if (isset($_POST['csrf_token'])) {
if (isset($_POST['update_user_level'])) {
if ($csrf_token->validate_token('update_user_level', $_POST['csrf_token'])) {

$user_id_user_level_form = '';
if (isset($_POST['user_id_user_level_form'])) {
$user_id_user_level_form = (INT) $_POST['user_id_user_level_form'];
}

$user_level_form = '';
if (isset($_POST['user_level_form'])) {
$user_level_form = trim($_POST['user_level_form']);
}

try {
$user = new User($this->dbh);
$user->set_user_id($user_id_user_level_form);
$user->set_user_level($user_level_form);
if ($user->update_user_level()) {
$message = new SystemMessage('The user level has been successfully updated.', 'msg_wrapper_mt', 'index.php?section=user_management', 'Return to user management');
echo $message->render_message();
require_once 'themes/backend_template/backend_footer.php';
exit();
}
} catch (Exception $e) {
include 'templates/messages/message.php';
require_once 'themes/backend_template/backend_footer.php';
exit();
}
} else {
$message = new SystemMessage('The session has expired for security reasons.', 'msg_wrapper_mt', 'index.php?section=user_management', 'Return to user management');
echo $message->render_message();
require_once 'themes/backend_template/backend_footer.php';
exit();
}
}
}

//Update user level.

//Remove a user, security question.

if (isset($_POST['csrf_token'])) {
if (isset($_POST['remove_user'])) {
if ($csrf_token->validate_token('remove_user', $_POST['csrf_token'])) {
$user_id_remove_user_form = '';
if (isset($_POST['user_id_remove_user_form'])) {
$user_id_remove_user_form = (INT) $_POST['user_id_remove_user_form'];
}
include 'templates/messages/message_remove_user.php';
require_once 'themes/backend_template/backend_footer.php';
exit();
} else {
$message = new SystemMessage('The session has expired for security reasons.', 'msg_wrapper_mt', 'index.php?section=user_management', 'Return to user management');
echo $message->render_message();
require_once 'themes/backend_template/backend_footer.php';
exit();
}
}
}

//Remove a user, security question.

//Remove a user.

if (isset($_POST['csrf_token'])) {
if (isset($_POST['remove_user_confirm'])) {
if ($csrf_token->validate_token('remove_user_confirm', $_POST['csrf_token'])) {

$user_id_remove_user_confirm_form = '';
if (isset($_POST['user_id_remove_user_confirm_form'])) {
$user_id_remove_user_confirm_form = (INT) $_POST['user_id_remove_user_confirm_form'];
}

try {
$user = new User($this->dbh);
$user->set_user_id($user_id_remove_user_confirm_form);
if ($user->remove_user()) {
$message = new SystemMessage('The user has been successfully removed.', 'msg_wrapper_mt', 'index.php?section=user_management', 'Return to user management');
echo $message->render_message();
require_once 'themes/backend_template/backend_footer.php';
exit();
}
} catch (Exception $e) {
include 'templates/messages/message.php';
require_once 'themes/backend_template/backend_footer.php';
exit();
}
} else {
$message = new SystemMessage('The session has expired for security reasons.', 'msg_wrapper_mt', 'index.php?section=user_management', 'Return to user management');
echo $message->render_message();
require_once 'themes/backend_template/backend_footer.php';
exit();
}
}
}

//Remove a user.

//Specify how many records should be displayed on each page.

$entries_per_page = 25;

//Specify how many records should be displayed on each page.

try {
$user = new User($this->dbh);
$total_records = $user->get_number_of_users();

//Pagination.

$current_page = isset($_GET['page']) ? (INT) $_GET['page'] : 1;
$pagination = new Pagination($entries_per_page, $current_page, $total_records);
$pagination->is_valid_page_number();
$offset = $pagination->get_offset();

//Pagination.

$user->set_entries_per_page($entries_per_page);
$user->set_offset($offset);
$users = $user->get_all_users();
if ($users === false) {
$message = new SystemMessage('Currently no users are registered.', 'msg_wrapper_mb');
echo $message->render_message();
} else {
include 'templates/backend_templates/user_management_template.php';
}

require_once 'themes/backend_template/backend_footer.php';

} catch (Exception $e) {
include 'templates/messages/message.php';
}
}

}
