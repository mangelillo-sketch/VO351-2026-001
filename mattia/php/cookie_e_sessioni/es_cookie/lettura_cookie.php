<?php
$cookie_name = "__Host-user_session";

// 1. Verifica esistenza del cookie
if (isset($_COOKIE[$cookie_name])) {
    $session_token = $_COOKIE[$cookie_name];

    // 2. Sanitizzazione base dell'input
    $session_token = htmlspecialchars($session_token, ENT_QUOTES, 'UTF-8');

    // 3. Verifica del token nel database o nel gestore di sessioni
    // (Esempio: verificare se $session_token appartiene a un utente attivo)
    if (validaTokenSessione($session_token)) {
        echo "Sessione valida. Benvenuto!";
    } else {
        // Token modificato o scaduto nel DB
        cancellaCookie();
    }
} else {
    echo "Nessun cookie di sessione trovato. Effettua il login.";
}

//solitamente si valida la chiamata
//verificando che la stringa sia valida
//e che esista nel database (nella tabella delle sessioni)
function validaTokenSessione($token) {
    // Esempio fittizio: qui inserisci la query al DB per validare l'hash del token
    return !empty($token) && strlen($token) === 64;
}

function cancellaCookie() {
    $cookie_name = "__Host-user_session";

    $cookie_options = [
        'expires'  => time() - 3600, // Data nel passato per forzare la cancellazione
        'path'     => '/',
        'domain'   => '',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ];

    setcookie($cookie_name, '', $cookie_options);
    unset($_COOKIE[$cookie_name]); // Rimuove la variabile anche dallo script corrente
}
?>