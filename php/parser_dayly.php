<?php
    require_once('parser_start.php');

    $period = 'Day';  // 'Day' or 'All'
    mysqli_query($mysqli, "SET FOREIGN_KEY_CHECKS = 0");
    mysqli_query($mysqli,"DELETE FROM `excursion` WHERE `date` BETWEEN CURRENT_DATE() - INTERVAL 1 DAY AND CURRENT_DATE() - INTERVAL 1 DAY");
    
    Parse::parseMS($period);
    Parse::parseMW($period);
    Parse::parseTM($period);

?>