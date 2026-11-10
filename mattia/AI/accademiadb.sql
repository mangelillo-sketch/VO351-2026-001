-- Database di supporto per i test di sicurezza di accademiadb
CREATE DATABASE IF NOT EXISTS accademiadb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE accademiadb;

DROP TABLE IF EXISTS utenti;

CREATE TABLE IF NOT EXISTS utenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    ruolo VARCHAR(50) DEFAULT 'studente',
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Record di prova
INSERT INTO utenti (nome, email, ruolo, password) VALUES
('Mario Rossi', 'mario.rossi@example.com', 'docente', '$2y$10$abcdefghijklmnopqrstuvwx1'),
('Luigi Bianchi', 'luigi.bianchi@example.com', 'studente', '$2y$10$abcdefghijklmnopqrstuvwx2'),
-- Record con payload XSS per testare la protezione contro Cross-Site Scripting
('<script>alert("XSS Vulnerability Detected!");</script>', 'hacker@example.com', 'guest', '$2y$10$abcdefghijklmnopqrstuvwx3');

