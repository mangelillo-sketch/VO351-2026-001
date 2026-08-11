<?php

$res = (is_numeric(12))?"è un numero":"non è un numero";

$val = 100;
$res = (is_numeric($val))?"è un numero":(is_array($val))?"è un array":"non è un array";