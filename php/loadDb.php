<?php
header('Content-type: text/html; charset=utf-8');
require_once('classes/simple_html_dom.php');
require_once('classes/Parse.php');
$mysqli = new mysqli('localhost','root','','excursions');

$mysqli -> query("TRUNCATE excursion");
Parse::parseMs();
Parse::parseMw();

?>