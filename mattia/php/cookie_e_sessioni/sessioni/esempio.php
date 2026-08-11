<?php
session_start();
$_SESSION['user_id'] = 345;//creo una variabile di sessione
$_SESSION['email'] = "email@me.it";//creo una variabile di sessione

echo $_SESSION['user_id'];