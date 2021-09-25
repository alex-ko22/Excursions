<?php
/**
 * Скрипт получения из базы всех полей для гида по его id
 */
header('Content-type: text/html; charset=utf-8'); 
require_once('db.php');
global $mysqli;

$guide_id = $_POST['guide_id'];

$result = mysqli_query($mysqli,"SELECT `guide`, `about`, `src_foto` FROM `guides` WHERE `id` = '$guide_id'");

$rec = $result->fetch_assoc();

echo json_encode($rec, JSON_UNESCAPED_UNICODE);
