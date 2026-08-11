<?php
//spaceship operator
/*echo 1<=>10;//-1
echo "<br />";
echo 1<=>1;//0
echo "<br />";
echo 10<=>1;
echo "<br />";
echo 3<=>10;
echo "<br />";
echo 9<=>9;
echo "<br />";
echo 9<=>2;
echo "<br />";
echo "A"<=>"E";
echo "<br />";
echo "A"<=>"A";
echo "<br />";
echo "B"<=>"E";
echo "<br />";
echo "Aa"<=>"z";
echo "<br />";
echo "Bb"<=>"Bc";
echo "<br />";*/

//null coalescing operator ??
/*$p = 56;
//$res=(isset($p) && !is_null($p))?$p:"valore di default";
$res = $p??"valore di default";

echo $res; */

//Elvis operator (op ternario abbreviato) ?:
$p = "ciao";
$res = $p?:"falso";

echo $res;