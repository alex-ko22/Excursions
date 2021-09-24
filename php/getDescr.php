<?php

header('Content-type: text/html; charset=utf-8'); 
require_once('db.php');
global $mysqli;

$exc_id = $_POST['exc_id'];

$result = mysqli_query($mysqli,"SELECT `descr`, `guide`, `img_url` FROM `excursion` LEFT JOIN `guides` ON `excursion`.`guide_id` = `guides`.`id` WHERE `exc_id` = '$exc_id'");

$rec = $result->fetch_assoc();

echo json_encode($rec, JSON_UNESCAPED_UNICODE);
