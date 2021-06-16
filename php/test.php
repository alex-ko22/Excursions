<?php
    header("Content-Type: text/plain; charset=utf-8");
    require_once('classes/Parse.php');


    $newDayStr = date('d-m-Y', strtotime('today + 5 day'));
    mkdir('../img/exc/'.$newDayStr);
    $updir = '../img/exc/'.$newDayStr.'/'; 
    $img_url = 'http://moscowwalking.ru/upload/resize_cache/iblock/f9e/760_560_2/83608.jpg';
    file_put_contents($updir.basename($img_url), file_get_contents($img_url));
?>