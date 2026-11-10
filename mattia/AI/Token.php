<?php

/**
 * Classe Token
 * Fornisce strumenti per la sicurezza applicativa:
 * - Generazione di token crittograficamente sicuri (random_bytes)
 * - Generazione codici OTP numerici monouso
 * - Protezione CSRF (Cross-Site Request Forgery) con confronto hash_equals a tempo costante
 * - Token firmati con HMAC e scadenza (stateless)
 * - Funzioni di hash per salvataggio sicuro su database
 */
class Token
{
    /**
     * Genera una stringa esadecimale casuale crittograficamente sicura.
     *
     * @param int $length Numero di byte casuali (stringa finale lunga $length * 2)
     * @return string
     */
    public static function generate(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Genera un codice numerico monouso a N cifre (es. OTP per SMS/Email 2FA).
     *
     * @param int $digits Numero di cifre (default 6)
     * @return string
     */
    public static function generateNumeric(int $digits = 6): string
    {
        $min = 10 ** ($digits - 1);
        $max = (10 ** $digits) - 1;
        return (string) random_int($min, $max);
    }

    /* =========================================================================
       PROTEZIONE CSRF
       ========================================================================= */

    private static function startSessionIfNeeded(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }

    /**
     * Genera o recupera il token CSRF associato alla sessione corrente.
     */
    public static function generateCsrf(string $sessionKey = 'csrf_token'): string
    {
        self::startSessionIfNeeded();

        if (empty($_SESSION[$sessionKey])) {
            $_SESSION[$sessionKey] = self::generate(32);
        }

        return $_SESSION[$sessionKey];
    }

    /**
     * Restituisce l'input hidden HTML contenente il token CSRF.
     */
    public static function csrfField(string $sessionKey = 'csrf_token'): string
    {
        $token = self::generateCsrf($sessionKey);
        $safeKey = htmlspecialchars($sessionKey, ENT_QUOTES, 'UTF-8');
        $safeToken = htmlspecialchars($token, ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="' . $safeKey . '" value="' . $safeToken . '">';
    }

    /**
     * Valida il token CSRF inviato rispetto a quello in sessione.
     * Usa hash_equals() per prevenire timing-attack.
     */
    public static function validateCsrf(?string $token, string $sessionKey = 'csrf_token', bool $regenerateAfter = false): bool
    {
        self::startSessionIfNeeded();

        if (empty($token) || empty($_SESSION[$sessionKey])) {
            return false;
        }

        $isValid = hash_equals($_SESSION[$sessionKey], $token);

        if ($regenerateAfter) {
            unset($_SESSION[$sessionKey]);
        }

        return $isValid;
    }

    /* =========================================================================
       TOKEN FIRMATI HMAC (es. Link Recupero Password con Scadenza)
       ========================================================================= */

    /**
     * Crea un token stateless firmato con scadenza.
     */
    public static function createSigned(array $payload, string $secretKey, int $ttlSeconds = 3600): string
    {
        $payload['exp'] = time() + $ttlSeconds;

        $jsonPayload = json_encode($payload);
        $encodedData = self::base64UrlEncode($jsonPayload);
        $signature   = hash_hmac('sha256', $encodedData, $secretKey);

        return $encodedData . '.' . $signature;
    }

    /**
     * Verifica la firma e la data di scadenza del token.
     */
    public static function verifySigned(string $token, string $secretKey): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            return null;
        }

        [$encodedData, $signature] = $parts;

        $expectedSignature = hash_hmac('sha256', $encodedData, $secretKey);
        if (!hash_equals($expectedSignature, $signature)) {
            return null; // Firma errata / alterazione
        }

        $payload = json_decode(self::base64UrlDecode($encodedData), true);
        if (!is_array($payload) || !isset($payload['exp'])) {
            return null;
        }

        if (time() > $payload['exp']) {
            return null; // Token scaduto
        }

        return $payload;
    }

    /* =========================================================================
       HASHING PER DATABASE
       ========================================================================= */

    public static function hash(string $plainToken): string
    {
        return hash('sha256', $plainToken);
    }

    public static function verifyHash(string $plainToken, string $hashedToken): bool
    {
        return hash_equals($hashedToken, self::hash($plainToken));
    }

    /* =========================================================================
       HELPER ENCODING
       ========================================================================= */

    public static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return (string) base64_decode(strtr($data, '-_', '+/'));
    }
}
