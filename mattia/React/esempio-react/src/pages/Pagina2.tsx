// ============================================================================
// FILE: src/pages/Pagina2.tsx
// SCOPO DIDATTICO: Pagina Destinatario - Ricezione dati via Link e Invio Risposta.
//
// CONCETTI CHIAVE ILLUSTRATI:
// 1. Estrazione dei Parametri: useSearchParams().get('nome') e .get('messaggio').
// 2. Fallback e Conditional Rendering: Mostra i dati se presenti, o un warning se mancanti.
// 3. Inoltro dei dati estratti dall'URL a un componente figlio tramite Props.
// 4. Link di ritorno parametrizzato: /navigazione?risposta=...
// 5. CSS Modules Isolati (Pagina2.module.css): Tema grafico verde smeraldo indipendente.
// ============================================================================

import { useState } from 'react'
import { Link, useSearchParams } from 'react-router-dom'
import styles from '../assets/css/Pagina2.module.css'
import SchedaMessaggio from '../components/SchedaMessaggio'

/**
 * PASSO 1: PROPS RICEVUTE DAL COMPONENTE PADRE (App.tsx).
 */
interface Pagina2Props {
  infoApp?: string
}

export default function Pagina2({ infoApp = 'Esempio React' }: Pagina2Props) {
  /**
   * PASSO 2: ESTRAZIONE DEI DATI DALL'URL.
   * L'hook useSearchParams() legge la query string della rotta corrente.
   * Se l'utente è arrivato tramite il link di Pagina 1:
   *   /pagina2?nome=Mario&messaggio=Ciao
   * searchParams.get('nome') conterrà "Mario"
   * searchParams.get('messaggio') conterrà "Ciao"
   */
  const [searchParams] = useSearchParams()
  const nome = searchParams.get('nome')
  const messaggio = searchParams.get('messaggio')

  /**
   * PASSO 3: STATO LOCALE PER LA RISPOSTA.
   * Pagina 2 può a sua volta formulare una risposta da rispedire a Pagina 1.
   */
  const [risposta, setRisposta] = useState<string>('Ricevuto! Tutto chiaro.')

  /**
   * PASSO 4: COMPOSIZIONE DEL LINK DI RITORNO.
   * Costruisce l'URL per tornare a Pagina 1 portando la risposta come parametro GET:
   *   /navigazione?risposta=...
   */
  const linkRisposta = `/navigazione?risposta=${encodeURIComponent(risposta)}`

  return (
    /**
     * PASSO 5: CSS MODULES A TEMA SMERALDO.
     * Le classi come styles.card e styles.btn usano lo stesso nome di Pagina 1,
     * ma grazie ai CSS Modules sono 100% isolate e colorate di verde!
     */
    <div className={styles.card}>
      <div className={styles.badge}>Pagina 2 (Tema Smeraldo)</div>
      <h2 className={styles.titolo}>Destinatario & Risposta</h2>
      
      <p className={styles.description}>
        Applicazione: <strong>{infoApp}</strong> (ricevuto come <strong>Prop</strong> da <code>App.tsx</code>)
      </p>

      {/**
       * PASSO 6: CONDITIONAL RENDERING DEI DATI RICEVUTI.
       * - Se 'messaggio' esiste nell'URL: mostriamo il box con i dati ricevuti
       *   e li passiamo al componente figlio <SchedaMessaggio /> tramite Props.
       * - Se 'messaggio' è null (es. accesso diretto alla pagina): mostriamo un avviso.
       */}
      {messaggio ? (
        <div className={styles.receivedBox}>
          <div className={styles.receivedHeader}>
            <span className="icon">📬</span>
            <h3>Dati estratti dal Link e passati come Props:</h3>
          </div>
          
          {/* I dati estratti dall'URL vengono passati come PROPS al componente figlio */}
          <SchedaMessaggio
            mittente={nome || 'Anonimo'}
            testo={messaggio}
            etichetta="Dati da Link -> Props"
            tema="verde"
          />

          <div className={styles.urlPreview}>
            <span>Parametri grezzi estratti dalla barra degli indirizzi:</span>
            <code>?nome={nome}&messaggio={messaggio}</code>
          </div>
        </div>
      ) : (
        /* Avviso mostrato se si naviga su Pagina 2 senza parametri */
        <div className={styles.alertWarning}>
          <div className={styles.alertTitle}>⚠️ Nessun parametro ricevuto nel link!</div>
          <p className={styles.alertBody}>
            Sei arrivato su questa pagina senza parametri nella query string (URL pulito).
            Torna alla <Link to="/navigazione" className={styles.inlineLink}>Pagina 1</Link> per inviare un messaggio tramite link.
          </p>
        </div>
      )}

      {/* SEZIONE FORM: Creazione di una risposta verso Pagina 1 */}
      <div className={styles.formSection}>
        <h3>Rispondi a Pagina 1 via Link</h3>
        <p className={styles.subtext}>
          Puoi inviare una risposta indietro a Pagina 1 aggiungendo <code>?risposta=...</code> al link di ritorno.
        </p>

        {/* Input controllato per la risposta */}
        <div className={styles.inputGroup}>
          <label htmlFor="risposta">La tua risposta:</label>
          <input
            id="risposta"
            type="text"
            value={risposta}
            onChange={(e) => setRisposta(e.target.value)}
            placeholder="Scrivi una risposta..."
          />
        </div>

        {/* Anteprima con SchedaMessaggio che riceve la risposta tramite props */}
        <h4>Anteprima Risposta (Componente con Props):</h4>
        <SchedaMessaggio
          mittente="Pagina 2"
          testo={risposta || 'Nessuna risposta'}
          etichetta="Anteprima Risposta"
          tema="verde"
        />

        <div className={styles.urlPreview}>
          <span>Link generato per il ritorno:</span>
          <code>{linkRisposta}</code>
        </div>

        {/* Link di navigazione che riporta i dati a Pagina 1 */}
        <Link to={linkRisposta} className={styles.btn}>
          ↩️ Invia risposta a Pagina 1 tramite Link
        </Link>
      </div>

      {/* Link rapidi di risposta preconfigurati */}
      <div className={styles.quickLinks}>
        <h3>Risposte rapide preimpostate:</h3>
        <div className={styles.linksGrid}>
          <Link
            to="/navigazione?risposta=Perfetto,%20messaggio%20ricevuto!"
            className={styles.quickLinkItem}
          >
            <strong>"Ricevuto!"</strong>
            <span>?risposta=Perfetto, messaggio ricevuto!</span>
          </Link>

          <Link
            to="/navigazione?risposta=Ho%20letto%20e%20ti%20richiamo%20tra%20poco!"
            className={styles.quickLinkItem}
          >
            <strong>"Ti richiamo tra poco"</strong>
            <span>?risposta=Ho letto e ti richiamo...</span>
          </Link>

          <Link to="/navigazione" className={`${styles.quickLinkItem} ${styles.simpleReturn}`}>
            <strong>Torna alla Pagina 1</strong>
            <span>(Senza passare parametri)</span>
          </Link>
        </div>
      </div>
    </div>
  )
}
