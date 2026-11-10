<?php
// CODICE LEGACY - DA SOTTOPORRE AD AUDIT AI
// Problemi: SQL Injection, XSS, assenza gestione errori,
// sintassi procedurale obsoleta.

$connessione = new mysqli("localhost", "root", "", "accademiadb");

// VULNERABILITÀ CRITICA: Nessuna sanificazione dell'input
$idUtente = $_GET['id'];
$query = "SELECT id, nome, email, ruolo
 FROM utenti WHERE id = " . $idUtente;

$risultato = $connessione->query($query);
$utente = $risultato->fetch_assoc();

// VULNERABILITÀ: Rischio XSS diretto
echo "<h1>Profilo: " . $utente['nome'] . "</h1>";
echo "<p>Email: " . $utente['email'] . "</p>";
?>
