-- CREATE ERROM DATABASE 
-- CREATE DATABASE eroom;

CREATE TABLE IF NOT EXISTS users (
    `id` INT(11) AUTO_INCREMENT,
    `uniqid` VARCHAR(255) NOT NULL,
    `username` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `gender` ENUM('male', 'female', '') DEFAULT '',
    `phone` VARCHAR(20) DEFAULT NULL,
    `avatar` VARCHAR(255) DEFAULT NULL,
    `role` VARCHAR(20) DEFAULT 'user',
    `location` VARCHAR(100) DEFAULT NULL,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `modified` DATETIME DEFAULT NULL,
    `online` ENUM('1','0','') DEFAULT '0',
    PRIMARY KEY (`id`)
)  ENGINE=INNODB;

INSERT INTO users (`uniqid`, `username`, `password`, `email`) VALUES ('_yedfbsdhjshjerdyuiog3etryrtyu5w','admin', 'admin123456', 'admin@mail.com');

CREATE TABLE IF NOT EXISTS history (
    `id` INT(11) AUTO_INCREMENT,
    `user_uid` VARCHAR(255) NOT NULL,
    `session_id` VARCHAR(255) NOT NULL,
    `action` VARCHAR(32) DEFAULT NULL,
    `message` TEXT DEFAULT NULL,
    `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS files (
    `id` INT(11) AUTO_INCREMENT,
    `uid` VARCHAR(255) NOT NULL,
    `related_to` VARCHAR(255) DEFAULT NULL,
    `name` VARCHAR(255) DEFAULT NULL,
    `true_name` VARCHAR(255) DEFAULT NULL,
    `description` TEXT DEFAULT NULL, 
    `type` VARCHAR(255) DEFAULT NULL,
    `path` TEXT NOT NULL,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `last_modified` DATETIME DEFAULT NULL,
    `last_accessed` DATETIME DEFAULT NULL,
    `attribute` ENUM('public', 'private', '') DEFAULT 'private',
    `access` TEXT DEFAULT NULL,
    `added_by` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`) 
) ENGINE=INNODB;