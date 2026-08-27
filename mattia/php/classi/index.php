<?php
require_once 'autoload.php';

//$obj è un oggetto di Utente
$obj = new Utente("Mattia", "Angelillo", "angelillo@istitutovolta.it", "ciao", "NGLM");
$obj->setNome("Mario");

echo $obj->getNome(), " ", $obj->getCognome(), "<br />";
echo $obj->getEmail(), " ", $obj->getCf(), "<br />";

$obj2 = new Utente("Giuseppe", "Verdi", "angelillo@istitutovolta.it", "ciao", "NGLM");

echo $obj2->getNome(), " ", $obj2->getCognome(), "<br />";
echo $obj2->getEmail(), " ", $obj2->getCf(), "<br />";

echo "<br /><br /><br />";
$studente = new Studente("Mario", "Bianchi", "fji@email.it", "ffg", "EHFEJJ", "000001", "ITPS");
echo $studente->getNome(), " ", $studente->getCognome(), " ", $studente->getEmail(), " ", $studente->getCf();
echo $studente->getMatricola(), " - ", $studente->getCorsoDiStudio();