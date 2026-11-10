<?php
/**
 * VERSIONE SICURA E REFACTORING MODERNO DI codice_legacy.php
 * 
 * Risoluzioni applicate:
 * 1. SQL Injection: eliminata tramite Prepared Statements con parametri vincolati (PDO).
 * 2. Cross-Site Scripting (XSS): neutralizzato con htmlspecialchars(..., ENT_QUOTES, 'UTF-8').
 * 3. Validazione Input: filter_input() con validazione numerica intera (FILTER_VALIDATE_INT).
 * 4. Gestione Errori: blocco try-catch con PDOException, codici di stato HTTP corretti (400, 404, 500)
 *    e messaggi generici all'utente per evitare Information Disclosure.
 * 5. Modernizzazione: passaggio a PDO e gestione pulita delle risorse.
 */

// 1. Validazione preliminare dell'input GET
$idUtente = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if ($idUtente === false || $idUtente === null) {
    http_response_code(400); // Bad Request
    die("<h1>400 - Richiesta non valida</h1><p>Parametro 'id' mancante o non valido (deve essere un intero positivo).</p>");
}

// Configurazione database (in produzione andrebbe in un file .env o config separato)
$dbHost = "localhost";
$dbPort = "3306";
$dbName = "accademiadb";
$dbUser = "root";
$dbPass = "";

try {
    // 2. Connessione con PDO in modalità eccezione
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lancia eccezioni in caso di errore
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Array associativo di default
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Prepared statement nativi del database
    ];

    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);

    // 3. Prepared statement per prevenire SQL Injection
    $stmt = $pdo->prepare("SELECT id, nome, email, ruolo FROM utenti WHERE id = :id");
    $stmt->execute(['id' => $idUtente]);
    $utente = $stmt->fetch();

    // 4. Gestione del caso in cui l'utente non esista nel database
    if (!$utente) {
        http_response_code(404); // Not Found
        die("<h1>404 - Utente non trovato</h1><p>Nessun profilo associato all'ID specificato.</p>");
    }

} catch (PDOException $e) {
    // 5. Prevenzione Information Disclosure: logga l'errore nel server ma non mostrarlo all'utente
    error_log("Database Error in codice_sicuro.php: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
    die("<h1>500 - Errore del server</h1><p>Si è verificato un errore durante il recupero dei dati. Riprova più tardi.</p>");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo Utente - Versione Sicura</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8fafc; color: #1e293b; }
        .card { background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 500px; }
        h1 { margin-top: 0; color: #0f172a; font-size: 1.5rem; }
        .badge { background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; }
    </style>
</head>
<body>

<div class="card">
    <!-- 6. Prevenzione XSS: escaping contestuale di tutti i dati stampati nell'HTML -->
    <h1>Profilo: <?= htmlspecialchars($utente['nome'], ENT_QUOTES, 'UTF-8') ?></h1>
    <p><strong>Email:</strong> <?= htmlspecialchars($utente['email'], ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Ruolo:</strong> <span class="badge"><?= htmlspecialchars($utente['ruolo'] ?? 'Standard', ENT_QUOTES, 'UTF-8') ?></span></p>
</div>

</body>
</html>

