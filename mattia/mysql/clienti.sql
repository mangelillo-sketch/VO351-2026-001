CREATE TABLE clienti (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE ordini (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id  INT NOT NULL,
    totale      DECIMAL(10,2) NOT NULL,-- 8 NUMERI PER L'INTERO, 2 NUMERO PER IL DECIMALE

    -- Vincolo di integrità referenziale:
    CONSTRAINT fk_ordini_clienti
        FOREIGN KEY (cliente_id)
        REFERENCES clienti(id)
        ON UPDATE CASCADE -- CASCADE, NO ACTION, SET NULL, RESTRICT
        ON DELETE CASCADE -- CASCADE, NO ACTION, SET NULL, RESTRICT
) ENGINE=InnoDB;


-- Inserimento di un record
INSERT INTO clienti (id, email, nome)
VALUES (NULL, "email@gmail.com", "Mario"), 
        (NULL, "giuseppe@gmail.com", "Giuseppe");

INSERT INTO clienti VALUES(NULL, "Nicola", "nicola@email.com");

INSERT INTO clienti VALUES(3, "Riccardo", "riccardo@email.com");

INSERT INTO ordini (cliente_id, totale)
VALUES (1, 250.15),(3, 50.15),(2, 18.0),(2, 150.0);

-- Delete di un record
DELETE FROM clienti WHERE id = 5;

-- Update di un record
UPDATE clienti
SET email = "giuseppeverdi@email.it"
 WHERE id = 2;

 UPDATE clienti
SET email = "giuseppeverdi@gmail.com",
    nome = "Giuseppe verdi"
 WHERE id = 2;

 -- SELECT -> selezioniamo i valori delle tabelle
 SELECT * FROM clienti;
 SELECT * FROM ordini;

 SELECT nome, email FROM clienti;
 SELECT nome, email FROM clienti WHERE id > 3;
 SELECT nome, email FROM clienti WHERE email LIKE "%email%";
 SELECT nome, email FROM clienti WHERE email LIKE "%email_it";
 SELECT nome, email FROM clienti WHERE email LIKE "%email_it" OR id = 1;
 SELECT nome, email FROM clienti WHERE email LIKE "%email_it" AND id = 1;

 -- INNER JOIN
SELECT
    c.nome,
    o.totale
FROM clienti c -- tabella di sinistra
INNER JOIN ordini o -- tabella di destra
    ON c.id = o.cliente_id
ORDER BY o.totale DESC;

-- LEFT JOIN
SELECT
    c.nome,
    COUNT(o.id)                    AS numero_ordini,
    AVG(o.totale) AS media_ordini,
    MIN(o.totale) AS ordine_minimo,
    MAX(o.totale) AS ordine_massimo,
    IFNULL(SUM(o.totale), 0.00) AS totale_speso
FROM clienti c -- tabella sx
LEFT JOIN ordini o -- LEFT OUTER JOIN | tabella di dx
    ON c.id = o.cliente_id
GROUP BY c.id, c.nome
ORDER BY totale_speso DESC;

SELECT
    c.nome,
    COUNT(o.id)                    AS numero_ordini,
    AVG(o.totale) AS media_ordini,
    MIN(o.totale) AS ordine_minimo,
    MAX(o.totale) AS ordine_massimo,
    IFNULL(SUM(o.totale), 0.00) AS totale_speso
FROM clienti c -- tabella sx
LEFT JOIN ordini o -- LEFT OUTER JOIN | tabella di dx
    ON c.id = o.cliente_id
GROUP BY c.id, c.nome
HAVING SUM(o.totale) > 100.00 -- clausola di raggruppamento
ORDER BY totale_speso DESC;

-- Modifica campo tabella ordini
ALTER TABLE ordini MODIFY COLUMN totale DECIMAL(10,2) NULL;
INSERT INTO ordini VALUES(NULL, 1, NULL);