<?php
    require_once('db.php');
    $mysqli -> query("SET FOREIGN_KEY_CHECKS = 0");
    $mysqli -> query("TRUNCATE guides");
    $mysqli -> query("SET FOREIGN_KEY_CHECKS = 0");
    $mysqli -> query("TRUNCATE excursion");
?>