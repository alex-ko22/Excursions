<?php
    header("Content-Type: text/plain; charset=utf-8");
    require_once('classes/Parse.php');
    echo(time().'  ');
    echo(microtime()."\n");
    echo(time().'  ');
    echo(microtime('as_float'));
?>