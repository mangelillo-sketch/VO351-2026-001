<?php
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

cancellaCookie();
?>