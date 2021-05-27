<?php
header('Content-type: text/html; charset=utf-8');
require_once('classes/simple_html_dom.php');
require_once('classes/Parse.php');
$mysqli = new mysqli('localhost','root','','excursions');

$mysqli -> query("TRUNCATE guides");
$mysqli -> query("SET FOREIGN_KEY_CHECKS = 0");
$mysqli -> query("TRUNCATE excursion");
//$mysqli -> query("SET FOREIGN_KEY_CHECKS=1");

$guidesArr = array();
$guideId = 1;

Parse::parseMs();
Parse::parseMw();
//Parse::parsePu();

?>