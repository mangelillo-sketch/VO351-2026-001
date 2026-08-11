<?php
// Avvia una sessione HTTPS o imposta l'header solo su connessione protetta
$cookie_name  = "__Host-user_session"; // Usare il prefisso __Host- impedisce la sovrascrittura da altri sottodomini
$cookie_value = bin2hex(random_bytes(32)); // Genera un ID token casuale e sicuro (non dati sensibili in chiaro)
$expiration   = time() + (3600 * 24 * 7); // Scadenza: 7 giorni

$cookie_options = [
    'expires'  => $expiration,
    'path'     => '/',               // Valido per tutto il dominio
    'domain'   => '',                // Con __Host- DEVE rimanere vuoto per vincolare il cookie all'host esatto
    'secure'   => true,              // Trasmissione SOLO via HTTPS
    'httponly' => true,              // Impedisce l'accesso via JavaScript (XSS protection)
    'samesite' => 'Strict'           // Protegge da attacchi CSRF ('Strict' o 'Lax')
];

// Impostazione del cookie
$result = setcookie($cookie_name, $cookie_value, $cookie_options);

if($result){
    echo "Cookie <strong>{$cookie_name}</strong> creato con successo!";
}else{
    echo "Errore nella creazione del cookie!";
}
?>