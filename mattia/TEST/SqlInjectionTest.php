<?php

require_once __DIR__ . '/SecurityTestCase.php';

/**
 * Suite di Test per la prevenzione di SQL Injection
 */
class SqlInjectionTest extends SecurityTestCase
{
    /**
     * Helper che simula la funzione di validazione input usata in codice_sicuro.php
     */
    private function validateIdInput($rawInput)
    {
        return filter_var($rawInput, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1]
        ]);
    }

    public function runAll(): void
    {
        $this->testFilterBlocksCommonSqlPayloads();
        $this->testFilterAcceptsValidIntegers();
        $this->testPreparedStatementNeutralizesInjection();
        $this->testVulnerableConcatenationFailsSecurityCheck();
    }

    /**
     * 1. Verifica che i vettori di SQL Injection vengano respinti a monte dal validatore numerico
     */
    public function testFilterBlocksCommonSqlPayloads(): void
    {
        $payloads = [
            '1 OR 1=1'                       => 'Tautologia classica',
            '1; DROP TABLE utenti;'          => 'Stacked query injection',
            "-1 UNION SELECT 1,'admin',3,4"  => 'UNION based injection',
            "1' AND SLEEP(3)--"              => 'Time-based blind injection',
            "' OR 'x'='x"                    => 'String injection',
            "0x31"                           => 'Hexadecimal encoding',
            "-5"                             => 'Intero negativo con min_range=1',
            "3.14"                           => 'Valore a virgola mobile',
            "abc"                            => 'Stringa alfabetica',
            ""                               => 'Stringa vuota'
        ];

        foreach ($payloads as $payload => $description) {
            $result = $this->validateIdInput($payload);
            $this->assert(
                $result === false,
                "SQLi Block: $description",
                "Il payload '$payload' deve essere rifiutato come ID non valido."
            );
        }
    }

    /**
     * 2. Verifica che gli ID legittimi vengano correttamente validati e castati
     */
    public function testFilterAcceptsValidIntegers(): void
    {
        $validInputs = [
            '1'    => 1,
            '42'   => 42,
            '9999' => 9999,
            15     => 15
        ];

        foreach ($validInputs as $input => $expected) {
            $result = $this->validateIdInput($input);
            $this->assertEquals(
                $expected,
                $result,
                "Valid Input: Intero valido '$input'",
                "Un ID valido deve essere accettato e convertito in int."
            );
        }
    }

    /**
     * 3. Dimostrazione su Database SQLite in-memory:
     *    Il Prepared Statement tratta il payload come dato letterale e NON esegue codice SQL.
     */
    public function testPreparedStatementNeutralizesInjection(): void
    {
        // Creazione DB in-memory per il test
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE TABLE utenti (id INTEGER PRIMARY KEY, nome TEXT, email TEXT)");
        $pdo->exec("INSERT INTO utenti (id, nome, email) VALUES (1, 'Mario', 'mario@test.it'), (2, 'Luigi', 'luigi@test.it')");

        // Payload malevolo che tenterebbe di estrarre tutto
        $maliciousPayload = "1 OR 1=1";

        // Prepared statement corretto:
        $stmt = $pdo->prepare("SELECT * FROM utenti WHERE id = :id");
        $stmt->execute(['id' => $maliciousPayload]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // SQLite tratterà "1 OR 1=1" confrontando con la colonna numerica:
        // non deve restituire il secondo utente (Luigi)!
        $foundLuigi = false;
        foreach ($results as $row) {
            if ($row['nome'] === 'Luigi') {
                $foundLuigi = true;
            }
        }

        $this->assert(
            !$foundLuigi,
            "Prepared Statement: Isolamento semantico dell'input",
            "Il prepared statement non deve permettere a '$maliciousPayload' di estrarre record non autorizzati."
        );
    }

    /**
     * 4. Verifica difensiva: dimostra che la concatenazione vulnerabile FALLISCE il test
     */
    public function testVulnerableConcatenationFailsSecurityCheck(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE TABLE utenti (id INTEGER PRIMARY KEY, nome TEXT, email TEXT)");
        $pdo->exec("INSERT INTO utenti (id, nome, email) VALUES (1, 'Mario', 'mario@test.it'), (2, 'Luigi', 'luigi@test.it')");

        $maliciousPayload = "1 OR 1=1";

        // Query vulnerabile concatenata (come in codice_legacy.php)
        $queryVulnerabile = "SELECT * FROM utenti WHERE id = " . $maliciousPayload;
        $risultato = $pdo->query($queryVulnerabile)->fetchAll(PDO::FETCH_ASSOC);

        // La query vulnerabile estrarrà TUTTI e 2 gli utenti
        $hasBoth = (count($risultato) === 2);

        $this->assert(
            $hasBoth,
            "Rilevamento vulnerabilità concatenazione legacy",
            "La query non parametrizzata viene alterata da '1 OR 1=1' ed estrae record multipli (comportamento vulnerabile)."
        );
    }
}

