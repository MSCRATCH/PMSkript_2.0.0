## PMSkript-2.0.0

PMSkript-2.0.0 is a PHP and MySQL based private messaging system.
It is a newly revised version of my previous procedurally written script.

A message can be sent as follows.

```
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
}
```

*Technologies*

![PHP](https://img.shields.io/badge/PHP-8.4-blue.svg)
![OOP PHP](https://img.shields.io/badge/PHP-OOP-blue.svg)
![MySQLi](https://img.shields.io/badge/MySQLi-blue.svg)
![HTML](https://img.shields.io/badge/HTML-5-orange.svg)
![CSS](https://img.shields.io/badge/CSS-3-blue.svg)
![GIMP](https://img.shields.io/badge/GIMP-2.x-blue.svg)

*Installation*

As there is no installation system available, manual installation is required. Here are the steps:

1. *Database setup*: Insert the database credentials into the `classes/Dbh.php` class.
2. *Create tables*: Create the necessary tables using the provided SQL code.
3. *Create user account*: Sign up for a new user account using the registration form.
4. *Assign administrator privileges*: Change the user level to administrator in phpMyAdmin.
