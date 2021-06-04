<?php
    header("Content-Type: text/plain; charset=utf-8");
    require_once('classes/Parse.php');

    $date = 'Пт 5 июня';
    $dateArr = explode(' ',$date);
    $dateStr = Parse::formDateMonth($date,$dateArr[1]);
    $dateStr = ' = "'.$dateStr.'"';
    $where = '`date`'.$dateStr;
    echo($where);
?>