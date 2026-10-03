DELIMITER //
CREATE PROCEDURE InserisciOrdineSicuro(
 IN p_cliente_id INT,
 IN p_importo DECIMAL(10,2),
 OUT p_esito VARCHAR(100)
)
BEGIN
    START TRANSACTION;
    
    IF EXISTS (SELECT * FROM clienti WHERE id = p_cliente_id) THEN
        INSERT INTO ordini (cliente_id, totale)
        VALUES (p_cliente_id, p_importo);
        COMMIT;
        SET p_esito = 'SUCCESS: Ordine registrato.';
    ELSE
        ROLLBACK;
        SET p_esito = 'ERRORE: Cliente non trovato.';
    END IF;
END //
DELIMITER ;

SET @esito = "";
CALL InserisciOrdineSicuro(1, 20000, @esito);
SELECT @esito;


DELIMITER //
CREATE PROCEDURE elencoOrdini()
BEGIN
    SELECT * FROM clienti c 
    JOIN ordini o ON c.id = o.cliente_id;
END //
DELIMITER ;

CALL elencoOrdini();

DELIMITER //
CREATE PROCEDURE getPrimoRisultatoByNome(INOUT n VARCHAR(255))
BEGIN
    SELECT nome INTO n FROM clienti c 
    JOIN ordini o ON c.id = o.cliente_id
    WHERE c.nome LIKE CONCAT("%", n, "%")
    LIMIT 1;
END //
DELIMITER ;

SET @res = "ar";
CALL getPrimoRisultatoByNome(@res);
SELECT @res;