<?php
$arr = array("Primo el", "Secondo El");
echo $arr[0], "<br />";
$arr[1] = "Nuovo secondo el";

$arr = ["Primo el", "Secondo El"];
echo $arr[0], "<br />";
$arr[1] = "Nuovo secondo el";

//Array associativi
$utente = [
    "cognome" => "Angelillo",
    "nome" => "Mattia",
];
echo sprintf("Nome: %s, Cognome: %s<br />", $utente['nome'], $utente['cognome']);

foreach($utente as $chiave => $valore){
    printf("[%s => %s]<br />", $chiave, $valore);
}
foreach($utente as $valore){
    printf("[%s]<br />", $valore);
}

print_r(array_change_key_case($utente, CASE_UPPER));

$arr = ["a", "b", "c", "d", "e"];

echo "<br /><pre>";
print_r(array_chunk($arr, 3));
var_dump(array_chunk($arr, 3));
echo "</pre>";

echo current($arr), "<br />";
next($arr);
next($arr);
echo next($arr), "<br />";
echo current($arr), "<br />";
prev($arr);
echo current($arr), "<br />";
echo end($arr), "<br />";
reset($arr);
echo current($arr), "<br />";
