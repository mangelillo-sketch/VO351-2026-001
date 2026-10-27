<?php
try {   
    $con = new PDO("mysql:host=127.0.0.1:3307;dbname=laravel_test", "root", "");

    $stmt = $con->prepare("SELECT * FROM libri WHERE titolo LIKE :pippo");
    $stmt->execute([
        'pippo' => '%titolo%'
    ]);
    $utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($utenti as $utente) {
        echo "ID: " . $utente['identificativo'] . "<br>";
        echo "Titolo: " . $utente['titolo'] . "<br>";
        echo "Autore: " . $utente['isbn'] . "<br>";
        echo "Anno: " . $utente['prezzo'] . "<br>";
        echo "<hr>";
    }

    $stmt = $con->prepare("INSERT INTO libri (titolo, isbn, prezzo) VALUES (:titolo, :isbn, :prezzo)");
    $stmt->execute([
        'titolo' => 'Titolo di esempio',
        'isbn' => '1234567890',
        'prezzo' => 19.99
    ]);
    
}catch(PDOException $e) {
    echo "Errore di connessione: " . $e->getMessage();
}