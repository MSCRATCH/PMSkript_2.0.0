<!DOCTYPE html>
<html lang="de">
<head>
<title><?php echo sanitize(name); ?></title>
<meta charset="utf-8">
<meta name="robots" content="INDEX,FOLLOW">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="pathologicalplay">
<meta name="revisit-after" content="2 days">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="themes/default_template/default_template.css" media="all" type="text/css">
</head>
<body>
<div class="template_row">
<div class="template_column_1">
<div class="template_nav">
<ul>
<li><a href="index.php"><span class="secondary_font_color"><?php echo sanitize(name); ?> <?php echo sanitize(version); ?></a></span></li>
<li><a href="index.php">Home</a></li>
<?php if (! Auth::is_logged_in()) { ?>
<li><a href="index.php?section=login">Login</a></li>
<li><a href="index.php?section=register">Register</a></li>
<?php } ?>
<?php if (Auth::is_logged_in()) { ?>
<?php $username_header = Auth::get_username(); ?>
<?php $user_id_header = Auth::get_user_id(); ?>
<?php $mailbox = new Mailbox($this->dbh); ?>
<?php $mailbox->set_user_id($user_id_header); ?>
<?php $number_of_new_messages = $mailbox->check_message_status(); ?>
<li><a href=""><?php echo sanitize_ucfirst($username_header);?></a></li>
<li><a href="index.php?section=new_message">New message</a></li>
<li><a href="index.php?section=view_inbox">Inbox <span class="secondary_font_color"><?php echo sanitize($number_of_new_messages);?></span></a></li>
<li><a href="index.php?section=view_outbox">Outbox</a></li>
<?php } ?>
<?php if (Auth::is_administrator()) { ?>
<li><a href="index.php?section=user_management">Administration</a></li>
<?php } ?>
<?php if (Auth::is_logged_in()) { ?>
<li><a href="index.php?section=logout">Logout</a></li>
<?php } ?>
</ul>
</div>
</div>
<div class="template_column_2">
<main>
