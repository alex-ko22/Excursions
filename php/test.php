<?php
    header("Content-Type: text/plain; charset=utf-8");
    require_once('classes/Parse.php');

    echo(date('d-m-Y', strtotime('today + 9 day')));
?>