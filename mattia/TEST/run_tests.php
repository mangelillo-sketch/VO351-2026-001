<?php

require_once __DIR__ . '/SqlInjectionTest.php';
require_once __DIR__ . '/XssTest.php';

// Colori ANSI per terminale
$green = "\033[32m";
$red   = "\033[31m";
$blue  = "\033[34m";
$bold  = "\033[1m";
$reset = "\033[0m";

echo "{$bold}{$blue}======================================================{$reset}\n";
echo "{$bold}   SUITE DI TEST DI SICUREZZA (SQL Injection & XSS)   {$reset}\n";
echo "{$bold}{$blue}======================================================{$reset}\n\n";

$suites = [
    'SQL Injection Defense Test' => new SqlInjectionTest(),
    'Cross-Site Scripting (XSS) Defense Test' => new XssTest()
];

$totalPassed = 0;
$totalFailed = 0;

foreach ($suites as $suiteName => $suite) {
    echo "{$bold}Running: {$suiteName}...{$reset}\n";
    $suite->runAll();

    foreach ($suite->getResults() as $res) {
        if ($res['status'] === 'PASSED') {
            echo "  [{$green}PASSED{$reset}] {$res['name']}\n";
        } else {
            echo "  [{$red}FAILED{$reset}] {$res['name']} - {$res['details']}\n";
        }
    }

    $totalPassed += $suite->getPassedCount();
    $totalFailed += $suite->getFailedCount();
    echo "\n";
}

$total = $totalPassed + $totalFailed;

echo "{$bold}{$blue}------------------------------------------------------{$reset}\n";
echo "{$bold}RIASSUNTO FINALE:{$reset}\n";
echo "Totale Test: {$total}\n";
echo "Superati:    {$green}{$totalPassed}{$reset}\n";
echo "Falliti:     " . ($totalFailed > 0 ? "{$red}{$totalFailed}{$reset}" : "0") . "\n";

if ($totalFailed === 0) {
    echo "\n{$bold}{$green}TUTTI I TEST DI DIFESA SONO STATI SUPERATI CON SUCCESSO!{$reset}\n";
} else {
    echo "\n{$bold}{$red}ATTENZIONE: ALCUNI TEST SONO FALLITI. VERIFICARE I DETTAGLI.{$reset}\n";
}

echo "{$bold}{$blue}======================================================{$reset}\n";

