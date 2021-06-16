<?php
    
    header('Content-type: text/html; charset=utf-8');
    require_once('classes/simple_html_dom.php');
    require_once('classes/Parse.php');
    require_once('db.php');
        
    $days = 9;  // Количество дней для загрузки в базу 
    $descrMax = 1499; // Максимальное количество символов в описании
    $imgDir = '';

    mysqli_query($mysqli, "SET FOREIGN_KEY_CHECKS = 0");
    mysqli_query($mysqli,"DELETE FROM `excursion` WHERE `date` BETWEEN CURRENT_DATE() - INTERVAL 1 DAY AND CURRENT_DATE() - INTERVAL 1 DAY");

    $newDayStr = date('d-m-Y', strtotime('today + '.$days.' day'));
    $imgDir = '../img/exc_imgs/'.$newDayStr.'/';
    mkdir($imgDir);

    $fOpen = fopen('../log/logFile.txt', 'a+');
    if ( !$fOpen){ 
        echo 'Wrong open log-file!';
    } 
    fwrite($fOpen, "\n".date('d-m-Y H:i:s')."\n");
    fwrite($fOpen,'New date: '.$newDayStr."\n");
    
    Parse::parseMS();
    Parse::parseMW();
    Parse::parseTM();
    Parse::parseMV();
    Parse::parseMH(); 
    Parse::parseMSt();

    fclose($fOpen);

?>