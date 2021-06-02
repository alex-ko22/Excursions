<?php

$str = '123\r456\r678';
echo($str);
echo(str_replace('\r',' ', $str));

?>