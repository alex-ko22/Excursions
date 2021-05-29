<?php

$strng = 'daasd sadd adsda asdasd saddad asddsaad';
$arr = explode(' ',$strng,35);
array_pop();

echo(implode(' ', $arr).'...');

?>