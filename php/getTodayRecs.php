<?php
    header('Content-type: text/html; charset=utf-8');
    session_start();
    
    $mysqli = new mysqli('localhost','root','','excursions');

    $result = mysqli_query($mysqli,"SELECT *
     FROM `excursion` LEFT JOIN `guides` ON `excursion`.`guide_id` = `guides`.`id`
        WHERE `date` = CURRENT_DATE()");
    
    $recs = [];
    while( $row = $result->fetch_assoc() ){
        $recs[] = $row;
    }
    
    echo json_encode($recs, JSON_UNESCAPED_UNICODE); 

?>