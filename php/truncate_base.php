<?php
    $mysqli = new mysqli('localhost','root','','excursions');
    $mysqli -> query("SET FOREIGN_KEY_CHECKS = 0");
    $mysqli -> query("TRUNCATE guides");
    $mysqli -> query("SET FOREIGN_KEY_CHECKS = 0");
    $mysqli -> query("TRUNCATE excursion");
?>