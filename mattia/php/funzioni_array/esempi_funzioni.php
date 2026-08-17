<?php

$var = false;

if(empty($var)){
    echo "la variabile è vuota";
}else{
    echo "La variabile vale: ", $var;
}
echo "<br />";

if(isset($test)){
    echo "la variabile esiste";
}else{
    echo "la variabile non esiste";
}
echo "<br />";

if(is_numeric(10)){
    echo "è un numero";
}else echo "non è un numero";
echo "<br />";

if(is_integer(10)){
    echo "è un numero intero";
}else echo "non è un numero";
echo "<br />";

if(is_float(10)){
    echo "è un numero float";
}else echo "non è un float";
echo "<br />";


$html = <<<JSCODE
<script type="text/javascript">
    alert("Codice eseguito");
</script>

<b>Lorem ipsum dolor sit amet</b>
JSCODE;

echo htmlspecialchars($html), "<br />";
echo strip_tags($html), "<br />";
echo strip_tags($html, "<b>"), "<br />";


$text = "Scaffale %s, posto %d, Codice %-'+10d";
echo sprintf($text, "A58",23, 136), "<br />";
$text = "Scaffale %s, posto %d, Codice %'+10d";
echo sprintf($text, "A58",23, 136), "<br />";
printf($text, "A58",23, 136);
echo "<br />", printf($text, "A58",23, 136), "<br />";

echo strpos("Hello World", "Wo"), "<br />";

$data = "25-01-1993";
$array = explode("-", $data);
echo "<pre>";
var_dump($data);
var_dump($array);
echo "</pre>";

$array = array(11, 8, 2026);
$str = implode(",", $array);
echo $str, "<br />";


//DATE
echo date("d-M-Y"), "<br />";
echo date("d-m-y H:i:s h:i:s a"), "<br />";
echo date("d-m-Y H:i:s h:i:s a", mktime(21, 15, 10, 1,1,2000)), "<br />";