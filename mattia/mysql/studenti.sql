CREATE TABLE IF NOT EXISTS studenti(
    id INT PRIMARY KEY NOT NULL,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    codice_fiscale CHAR(16) NOT NULL UNIQUE,
    data_iscrizione TIMESTAMP NOT NULL,-- TIMESTAMP ON DEFAULT NOW()
    ultima_modifica TIMESTAMP ON UPDATE now() NOT NULL,
    stato_account VARCHAR(20) DEFAULT 'attivo',

    CONSTRAINT chk_stato  CHECK(
        stato_account IN ('attivo', 'sospeso', 'inattivo')
    )
)ENGINE = INNODB
CHARSET = utf8mb4
COLLATE = utf8mb4_unicode_ci;