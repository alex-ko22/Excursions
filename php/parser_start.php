<?php
    header('Content-type: text/html; charset=utf-8');
    require_once('classes/simple_html_dom.php');
    require_once('classes/Parse.php');
    
    $mysqli = new mysqli('localhost','root','','excursions');
    
    // Количество дней для загрузки в базу
    $days = 9;
    // Максимальное количество символов в описании
    $descrMax = 1499;
    $period = 'Day';
?>