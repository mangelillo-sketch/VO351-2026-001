<?php
require_once __DIR__ . '/SqlInjectionTest.php';
require_once __DIR__ . '/XssTest.php';

// Esecuzione dei test
$sqliTest = new SqlInjectionTest();
$sqliTest->runAll();

$xssTest = new XssTest();
$xssTest->runAll();

$totalPassed = $sqliTest->getPassedCount() + $xssTest->getPassedCount();
$totalFailed = $sqliTest->getFailedCount() + $xssTest->getFailedCount();
$totalTests  = $totalPassed + $totalFailed;
$passPercentage = $totalTests > 0 ? round(($totalPassed / $totalTests) * 100) : 0;

// Sandbox Interattiva: test di un payload personalizzato inserito dall'utente
$customInput = $_POST['custom_payload'] ?? '';
$sandboxType = $_POST['sandbox_type'] ?? 'all';
$sandboxResults = null;

if (!empty($customInput)) {
    // 1. Test SQL Validation (come in codice_sicuro.php)
    $sqlFilterResult = filter_var($customInput, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $sqlBlocked = ($sqlFilterResult === false);

    // 2. Test XSS Escaping (come in codice_sicuro.php)
    $xssEscaped = htmlspecialchars($customInput, ENT_QUOTES, 'UTF-8');
    $xssNeutralized = (strpos($xssEscaped, '<') === false && strpos($xssEscaped, '>') === false && strpos($xssEscaped, '"') === false);

    $sandboxResults = [
        'sql_blocked'      => $sqlBlocked,
        'sql_output'       => $sqlFilterResult,
        'xss_escaped'      => $xssEscaped,
        'xss_neutralized'  => $xssNeutralized
    ];
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Suite di Sicurezza - SQLi & XSS</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #f8fafc; color: #1e293b; margin: 0; padding: 24px; }
        .container { max-width: 980px; margin: 0 auto; }
        h1 { color: #0f172a; margin-bottom: 6px; }
        p.subtitle { color: #64748b; margin-top: 0; margin-bottom: 24px; }
        .summary-card { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; border-left: 6px solid #16a34a; }
        .metric { font-size: 2rem; font-weight: bold; color: #16a34a; }
        .metric-label { font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .card { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 24px; border: 1px solid #e2e8f0; }
        .card h2 { margin-top: 0; font-size: 1.2rem; color: #1e3a8a; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; display: flex; justify-content: space-between; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }
        th { background: #f8fafc; color: #475569; font-weight: 600; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: bold; }
        .badge-passed { background: #dcfce7; color: #15803d; }
        .badge-failed { background: #fee2e2; color: #b91c1c; }
        pre, code { font-family: Consolas, Monaco, monospace; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 4px; padding: 2px 6px; font-size: 0.85rem; }
        input[type="text"] { width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.95rem; margin-top: 8px; margin-bottom: 12px; }
        button { background: #2563eb; color: white; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        button:hover { background: #1d4ed8; }
        .result-box { background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 14px; margin-top: 12px; }
    </style>
</head>
<body>

<div class="container">
    <h1>🛡️ Suite di Test di Sicurezza</h1>
    <p class="subtitle">Verifica automatizzata delle difese contro <b>SQL Injection</b> e <b>Cross-Site Scripting (XSS)</b></p>

    <!-- METRICHE RIASSUNTIVE -->
    <div class="summary-card">
        <div>
            <div class="metric"><?= $passPercentage ?>%</div>
            <div class="metric-label">Tasso di Successo Difese</div>
        </div>
        <div>
            <div class="metric" style="color: #0f172a;"><?= $totalTests ?></div>
            <div class="metric-label">Test Eseguiti</div>
        </div>
        <div>
            <div class="metric"><?= $totalPassed ?></div>
            <div class="metric-label">Superati</div>
        </div>
        <div>
            <div class="metric" style="color: <?= $totalFailed > 0 ? '#dc2626' : '#64748b' ?>;"><?= $totalFailed ?></div>
            <div class="metric-label">Falliti</div>
        </div>
    </div>

    <!-- SANDBOX INTERATTIVA -->
    <div class="card" style="border-left: 6px solid #2563eb;">
        <h2>🧪 Sandbox: Testa un Payload a Piacere</h2>
        <p>Digita una stringa o vettore di attacco per vedere in tempo reale come viene processato dalle contromisure del codice sicuro:</p>

        <form method="POST" action="">
            <label for="custom_payload"><b>Inserisci Payload:</b></label>
            <input type="text" id="custom_payload" name="custom_payload" 
                   value="<?= htmlspecialchars($customInput ?: "1 OR 1=1; <script>alert('XSS')</script>") ?>" required>
            <button type="submit">Analizza Difese</button>
        </form>

        <?php if ($sandboxResults): ?>
            <div class="result-box">
                <h3>Esito Valutazione:</h3>
                <p>
                    <b>1. Validatore ID (Filtro Anti-SQLi):</b> 
                    <?php if ($sandboxResults['sql_blocked']): ?>
                        <span class="badge badge-passed">BLOCCATO CON SUCCESSO (400 Bad Request)</span>
                        <br><small>Il filtro <code>FILTER_VALIDATE_INT</code> rifiuta la stringa prima di toccare il database.</small>
                    <?php else: ?>
                        <span class="badge" style="background:#fef3c7; color:#b45309;">ACCETTATO COME NUMERO: <?= $sandboxResults['sql_output'] ?></span>
                    <?php endif; ?>
                </p>
                <p>
                    <b>2. Escaping HTML (Filtro Anti-XSS):</b>
                    <?php if ($sandboxResults['xss_neutralized']): ?>
                        <span class="badge badge-passed">NEUTRALIZZATO CON SUCCESSO</span>
                    <?php else: ?>
                        <span class="badge badge-passed">ESCAPED</span>
                    <?php endif; ?>
                    <br>
                    Output renderizzato: <code><?= htmlspecialchars($sandboxResults['xss_escaped']) ?></code>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- TABELLA TEST SQL INJECTION -->
    <div class="card">
        <h2>
            <span>1. Test Suite: Prevenzione SQL Injection</span>
            <span class="badge badge-passed"><?= $sqliTest->getPassedCount() ?> Superati</span>
        </h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 100px;">Stato</th>
                    <th>Nome Test / Vettore</th>
                    <th>Dettagli / Asserzione</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sqliTest->getResults() as $res): ?>
                    <tr>
                        <td><span class="badge badge-<?= strtolower($res['status']) ?>"><?= $res['status'] ?></span></td>
                        <td><b><?= htmlspecialchars($res['name']) ?></b></td>
                        <td><small><?= htmlspecialchars($res['details']) ?></small></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- TABELLA TEST XSS -->
    <div class="card">
        <h2>
            <span>2. Test Suite: Prevenzione Cross-Site Scripting (XSS)</span>
            <span class="badge badge-passed"><?= $xssTest->getPassedCount() ?> Superati</span>
        </h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 100px;">Stato</th>
                    <th>Nome Test / Vettore</th>
                    <th>Dettagli / Asserzione</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($xssTest->getResults() as $res): ?>
                    <tr>
                        <td><span class="badge badge-<?= strtolower($res['status']) ?>"><?= $res['status'] ?></span></td>
                        <td><b><?= htmlspecialchars($res['name']) ?></b></td>
                        <td><small><?= htmlspecialchars($res['details']) ?></small></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

