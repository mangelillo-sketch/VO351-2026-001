<?php
/**
 * VERSIONE SICURA TRAMITE MYSQLI AD OGGETTI
 * Mantiene la libreria mysqli originaria ma applica:
 * - Validazione input (filter_input)
 * - Prepared Statements (prepare + bind_param) contro SQL Injection
 * - Escaping dell'output (htmlspecialchars) contro XSS
 * - Gestione delle eccezioni con blocco try-catch (mysqli_sql_exception)
 */

// 1. Validazione input con filter_input
$idUtente = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if ($idUtente === false || $idUtente === null) {
    http_response_code(400);
    die("<h1>400 - Richiesta non valida</h1><p>Parametro 'id' mancante o non valido (deve essere un intero positivo).</p>");
}

// 2. Abilita le eccezioni per mysqli (standard in PHP 8.1+)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Connessione con charset esplicito
    $connessione = new mysqli("localhost", "root", "", "accademiadb");
    $connessione->set_charset("utf8mb4");

    // 3. Prepared Statement con segnaposto '?' per neutralizzare SQL Injection
    $stmt = $connessione->prepare("SELECT id, nome, email, ruolo FROM utenti WHERE id = ?");
    $stmt->bind_param("i", $idUtente); // "i" indica tipo integer
    $stmt->execute();

    $risultato = $stmt->get_result();
    $utente = $risultato->fetch_assoc();

    // 4. Controllo esistenza utente
    if (!$utente) {
        http_response_code(404);
        die("<h1>404 - Utente non trovato</h1><p>Nessun utente corrisponde all'ID fornito.</p>");
    }

} catch (mysqli_sql_exception $e) {
    error_log("Database Error in codice_sicuro_mysqli.php: " . $e->getMessage());
    http_response_code(500);
    die("<h1>500 - Errore interno del server</h1><p>Si è verificato un errore nel recupero dei dati. Riprova più tardi.</p>");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo Utente (MySQLi Sicuro)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8fafc; color: #1e293b; }
        .card { background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 500px; }
        h1 { margin-top: 0; color: #0f172a; font-size: 1.5rem; }
        .badge { background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; }
    </style>
</head>
<body>

<div class="card">
    <!-- 5. Prevenzione XSS: escaping dell'output HTML -->
    <h1>Profilo: <?= htmlspecialchars($utente['nome'], ENT_QUOTES, 'UTF-8') ?></h1>
    <p><strong>Email:</strong> <?= htmlspecialchars($utente['email'], ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Ruolo:</strong> <span class="badge"><?= htmlspecialchars($utente['ruolo'] ?? 'Standard', ENT_QUOTES, 'UTF-8') ?></span></p>
</div>

</body>
</html>

