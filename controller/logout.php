<?php
//logout.php [Logout the respective user.]
//Logout_controller

class Logout_controller {

function __construct($dbh) {
$this->dbh = $dbh;
}

public function index() {

if (! Auth::is_logged_in()) {
header('Location: index.php');
}

unset($_SESSION['logged_in']['username']);
unset($_SESSION['logged_in']['user_level']);
unset($_SESSION['logged_in']['user_id']);
require_once 'themes/default_template/header.php';
$message = new SystemMessage('You have been successfully logged out.', 'msg_wrapper_mt', 'index.php', 'Back to homepage');
echo $message->render_message();
require_once 'themes/default_template/footer.php';
exit();
}

}
