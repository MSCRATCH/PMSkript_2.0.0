CREATE TABLE `users` (
`user_id` int(10) NOT NULL AUTO_INCREMENT,
`username` varchar(30) NOT NULL,
`user_password` char(255) NOT NULL,
`user_email` varchar(50) NOT NULL,
`user_level` varchar(50) NOT NULL DEFAULT 'not_activated',
`user_date` datetime NOT NULL,
`last_activity` datetime NOT NULL,
PRIMARY KEY (`user_id`),
UNIQUE KEY `username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci

CREATE TABLE `mailbox` (
`message_id` int(10) NOT NULL AUTO_INCREMENT,
`message` text NOT NULL,
`message_title` varchar(150) NOT NULL,
`sent_by_id` int(10) NOT NULL,
`sent_to_id` int(10) NOT NULL,
`message_token` char(64) NOT NULL,
`message_created` datetime NOT NULL,
PRIMARY KEY (`message_id`),
KEY `sent_by_id` (`sent_by_id`),
KEY `sent_to_id` (`sent_to_id`),
CONSTRAINT `mailbox_ibfk_1` FOREIGN KEY (`sent_by_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `mailbox_ibfk_2` FOREIGN KEY (`sent_to_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci

CREATE TABLE `user_mailboxes` (
`user_mailbox_id` int(10) NOT NULL AUTO_INCREMENT,
`user_id` int(10) NOT NULL,
`mailbox` varchar(25) NOT NULL,
`message_status` tinyint(1) NOT NULL,
`message_id` int(10) NOT NULL,
PRIMARY KEY (`user_mailbox_id`),
KEY `user_id` (`user_id`),
KEY `message_id` (`message_id`),
CONSTRAINT `user_mailboxes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `user_mailboxes_ibfk_2` FOREIGN KEY (`message_id`) REFERENCES `mailbox` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci
