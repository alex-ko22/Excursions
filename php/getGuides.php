<?php
/**
 * Скрипт получения данных по всем гидам
 */
header('Content-type: text/html; charset=utf-8'); 
require_once('db.php');
global $mysqli;

$result = mysqli_query($mysqli,"SELECT `id`, `guide` FROM `guides` ORDER BY `guide`");

$recs = [];
while( $row = $result->fetch_assoc() ){
    $recs[] = $row;
}

echo json_encode($recs, JSON_UNESCAPED_UNICODE);
