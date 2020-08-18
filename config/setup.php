<?php
include "database.php";

$user_table = "CREATE TABLE IF NOT EXISTS `camagru`.`users` ( 
    `id` INT(11) NOT NULL AUTO_INCREMENT ,
    `username` VARCHAR(100) NOT NULL , 
    `password` TEXT NOT NULL ,
    `mail` VARCHAR(100) NOT NULL ,
    `token` varchar(300) NOT NULL,
    `verified` tinyint(1) DEFAULT NULL,
    `allow_mail` tinyint(1) DEFAULT '1',
    `is_admin` tinyint(1) DEFAULT NULL,
    `code` int(7) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

$photo_table = "CREATE TABLE IF NOT EXISTS `camagru`.`photoes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT , 
    `user_id` INT(11) NOT NULL , 
    `photo` text NOT NULL , 
    `pubdate` DATETIME NOT NULL ,
    `geolocation` VARCHAR(300) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

$comments_table = "CREATE TABLE IF NOT EXISTS `camagru`.`comments` (
    `id` INT(11) NOT NULL AUTO_INCREMENT ,
    `photo_id` INT(11) NOT NULL ,
    `from_id` INT(11) NOT NULL ,
    `pubdate` DATETIME NOT NULL ,
    `text` TEXT NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

$likes_table = "CREATE TABLE IF NOT EXISTS `camagru`.`likes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT ,
    `photo_id` INT(11) NOT NULL ,
    `from_id` INT(11) NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

$db = "CREATE DATABASE IF NOT EXISTS camagru;";
$mysqli = new mysqli('localhost', $DB_USER, $DB_PASSWORD);
if ($mysqli->connect_errno) {
    printf("Не удалось подключиться: %s\n", $mysqli->connect_error);
    exit();
}
if ($mysqli->query($db) !== TRUE) {
    printf("Ошибка создание БД.\n");
}

try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $ex) {
    echo $ex.getMessage();
}
$pdo->exec($likes_table . $comments_table . $photo_table . $user_table);
?>
