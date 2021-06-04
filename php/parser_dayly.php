<?php
    //require_once('parser_start.php');
    header('Content-type: text/html; charset=utf-8');
    require_once('classes/simple_html_dom.php');
    require_once('classes/Parse.php');
    require_once('db.php');
        
    $days = 9;  // Количество дней для загрузки в базу 
    $descrMax = 799; // Максимальное количество символов в описании
    $period = 'Day';  // 'Day' or 'All'

    mysqli_query($mysqli, "SET FOREIGN_KEY_CHECKS = 0");
    mysqli_query($mysqli,"DELETE FROM `excursion` WHERE `date` BETWEEN CURRENT_DATE() - INTERVAL 1 DAY AND CURRENT_DATE() - INTERVAL 1 DAY");
    
    Parse::parseMS($period);
    Parse::parseMW($period);
    Parse::parseTM($period);
    Parse::parseMV($period);
    Parse::parseMH($period);

?>