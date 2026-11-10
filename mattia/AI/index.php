<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
require_once __DIR__ . '/Token.php';

$secretKey = "chiave_segreta_dimostrativa";
$feedbackCsrf = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'test_csrf') {
    $tokenRicevuto = $_POST['csrf_token'] ?? '';
    if (Token::validateCsrf($tokenRicevuto)) {
        $feedbackCsrf = ['status' => 'success', 'message' => 'Token CSRF VALIDO! Richiesta accettata in sicurezza.'];
    } else {
        $feedbackCsrf = ['status' => 'danger', 'message' => 'Errore CSRF: Token MANOMESSO o NON valido!'];
    }
}

$randomToken = Token::generate(16);
$otp = Token::generateNumeric(6);
$signedToken = Token::createSigned(['user_id' => 15, 'role' => 'admin'], $secretKey, 3600);
$verified = Token::verifySigned($signedToken, $secretKey);
$plainToken = Token::generate(32);
$hashToken = Token::hash($plainToken);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorio di Sicurezza Web - Cartella AI</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #f1f5f9; color: #1e293b; margin: 0; padding: 24px; }
        .container { max-width: 960px; margin: 0 auto; }
        h1 { color: #0f172a; margin-bottom: 8px; }
        p.subtitle { color: #64748b; margin-top: 0; margin-bottom: 24px; font-size: 1.05rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .card { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; }
        .card h2 { margin-top: 0; font-size: 1.15rem; color: #1e3a8a; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; }
        .card-danger h2 { color: #b91c1c; }
        .card-success h2 { color: #15803d; }
        ul.test-links { list-style: none; padding: 0; margin: 0; }
        ul.test-links li { margin-bottom: 8px; }
        a.btn-test { display: block; text-decoration: none; padding: 8px 12px; border-radius: 6px; font-size: 0.85rem; font-family: Consolas, monospace; }
        .btn-normal { background: #e0f2fe; color: #0369a1; }
        .btn-attack { background: #fee2e2; color: #991b1b; }
        .btn-test:hover { opacity: 0.85; }
        pre, code { font-family: Consolas, Monaco, monospace; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; padding: 2px 6px; font-size: 0.85rem; }
        pre { padding: 12px; overflow-x: auto; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: bold; }
        .badge-red { background: #fecaca; color: #991b1b; }
        .badge-green { background: #bbf7d0; color: #166534; }
        .alert { padding: 10px 14px; border-radius: 6px; font-weight: 500; margin-bottom: 12px; }
        .alert-success { background: #dcfce7; color: #15803d; }
        .alert-danger { background: #fee2e2; color: #b91c1c; }
        button { background: #2563eb; color: white; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        button:hover { background: #1d4ed8; }
        button.btn-red { background: #dc2626; }
        button.btn-red:hover { background: #b91c1c; }
    </style>
</head>
<body>

<div class="container">
    <h1>🛡️ Laboratorio di Sicurezza Web (Cartella AI)</h1>
    <p class="subtitle">Confronto vulnerabilità, refactoring sicuro (PDO / MySQLi) e classe Token.</p>

    <!-- GRID CONFRONTO SCRIPT -->
    <div class="grid">
        <!-- CODICE LEGACY -->
        <div class="card card-danger">
            <h2>⚠️ Codice Legacy (Vulnerabile)</h2>
            <p><small><code>codice_legacy.php</code></small> <span class="badge badge-red">Vulnerabile</span></p>
            <p>Contiene SQL Injection, XSS e nessuna gestione degli errori.</p>
            <ul class="test-links">
                <li><a class="btn-test btn-normal" href="codice_legacy.php?id=1" target="_blank">Esecuzione normale (?id=1)</a></li>
                <li><a class="btn-test btn-attack" href="codice_legacy.php?id=1%20OR%201=1" target="_blank">Attacco SQL Injection (?id=1 OR 1=1)</a></li>
                <li><a class="btn-test btn-attack" href="codice_legacy.php?id=3" target="_blank">Attacco XSS Stored (?id=3)</a></li>
                <li><a class="btn-test btn-attack" href="codice_legacy.php" target="_blank">Parametro Mancante (Warning PHP)</a></li>
            </ul>
        </div>

        <!-- CODICE SICURO PDO -->
        <div class="card card-success">
            <h2>✅ Codice Sicuro (PDO)</h2>
            <p><small><code>codice_sicuro.php</code></small> <span class="badge badge-green">Protetto</span></p>
            <p>Prepared statements, <code>filter_input</code>, <code>htmlspecialchars</code> e codici HTTP.</p>
            <ul class="test-links">
                <li><a class="btn-test btn-normal" href="codice_sicuro.php?id=1" target="_blank">Esecuzione normale (?id=1)</a></li>
                <li><a class="btn-test btn-normal" href="codice_sicuro.php?id=1%20OR%201=1" target="_blank">Test SQL Injection (Bloccato 400)</a></li>
                <li><a class="btn-test btn-normal" href="codice_sicuro.php?id=3" target="_blank">Test XSS (Neutralizzato in testo)</a></li>
                <li><a class="btn-test btn-normal" href="codice_sicuro.php" target="_blank">Test Parametro Mancante (400)</a></li>
            </ul>
        </div>

        <!-- CODICE SICURO MYSQLI -->
        <div class="card card-success">
            <h2>✅ Codice Sicuro (MySQLi)</h2>
            <p><small><code>codice_sicuro_mysqli.php</code></small> <span class="badge badge-green">Protetto</span></p>
            <p>Mantiene MySQLi ma usa <code>prepare()</code> e <code>bind_param("i")</code>.</p>
            <ul class="test-links">
                <li><a class="btn-test btn-normal" href="codice_sicuro_mysqli.php?id=1" target="_blank">Esecuzione normale (?id=1)</a></li>
                <li><a class="btn-test btn-normal" href="codice_sicuro_mysqli.php?id=1%20OR%201=1" target="_blank">Test SQL Injection (Bloccato 400)</a></li>
                <li><a class="btn-test btn-normal" href="codice_sicuro_mysqli.php?id=3" target="_blank">Test XSS (Neutralizzato in testo)</a></li>
                <li><a class="btn-test btn-normal" href="codice_sicuro_mysqli.php" target="_blank">Test Parametro Mancante (400)</a></li>
            </ul>
        </div>
    </div>

    <!-- SEZIONE TOKEN -->
    <div class="card">
        <h2>🔑 Test Funzionalità Classe <code>Token.php</code></h2>

        <!-- CSRF TEST -->
        <h3>1. Protezione Form CSRF</h3>
        <?php if ($feedbackCsrf): ?>
            <div class="alert alert-<?= $feedbackCsrf['status'] ?>"><?= htmlspecialchars($feedbackCsrf['message']) ?></div>
        <?php endif; ?>

        <form method="POST" action="" style="display: inline-block; margin-right: 8px;">
            <input type="hidden" name="action" value="test_csrf">
            <?= Token::csrfField() ?>
            <button type="submit">Invia Form con Token CSRF Valido</button>
        </form>

        <form method="POST" action="" style="display: inline-block;">
            <input type="hidden" name="action" value="test_csrf">
            <input type="hidden" name="csrf_token" value="token_manomesso_xyz">
            <button type="submit" class="btn-red">Simula Attacco CSRF (Token Falso)</button>
        </form>

        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">

        <!-- ALTRI METODI TOKEN -->
        <h3>2. Generatore e Token Firmati</h3>
        <p><b>Token Casuale (Hex 32 char):</b> <code><?= $randomToken ?></code></p>
        <p><b>Codice OTP a 6 cifre:</b> <code><?= $otp ?></code></p>
        <p><b>Token Firmato HMAC (Link con scadenza 1h):</b><br><code><?= $signedToken ?></code></p>
        <p><b>Dati decodificati dopo verifica:</b></p>
        <pre><?= htmlspecialchars(print_r($verified, true)) ?></pre>
        <p><b>Hash per Database:</b> Token in chiaro: <code><?= $plainToken ?></code> &rarr; Hash SHA-256 salvato a DB: <code><?= $hashToken ?></code></p>
    </div>
</div>

</body>
</html>

