<?php

header('Content-type: text/html; charset=utf-8'); 
require_once('db.php');

$guide_id = $_POST['guide_id'];

$result = mysqli_query($mysqli,"SELECT `guide`, `about`, `src_foto` FROM `guides` WHERE `id` = '$guide_id'");

$rec = $result->fetch_assoc();

echo json_encode($rec, JSON_UNESCAPED_UNICODE);
?>