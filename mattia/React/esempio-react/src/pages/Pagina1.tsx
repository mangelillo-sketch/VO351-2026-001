// ============================================================================
// FILE: src/pages/Pagina1.tsx
// SCOPO DIDATTICO: Pagina Mittente - Comunicazione tra pagine tramite Link & Props.
//
// CONCETTI CHIAVE ILLUSTRATI:
// 1. Props a livello di pagina: Pagina1 riceve 'infoApp' direttamente da App.tsx.
// 2. Hook useSearchParams (react-router-dom): Legge i parametri GET dall'URL (es. ?risposta=...).
// 3. Hook useState: Gestisce in tempo reale i campi di input 'nome' e 'messaggio'.
// 4. Composizione dell'URL Query String con encodeURIComponent().
// 5. CSS Modules: Importazione e utilizzo di classi grafiche isolate (styles.card, styles.btn).
// 6. Componente Figlio con Props: SchedaMessaggio viene utilizzata per l'anteprima live.
// 7. Componente Link: Navigazione SPA senza ricaricamento della pagina.
// ============================================================================

import { useState } from 'react'
import { Link, useSearchParams } from 'react-router-dom'
import styles from '../assets/css/Pagina1.module.css'
import SchedaMessaggio from '../components/SchedaMessaggio'

/**
 * PASSO 1: PROPS DELLA PAGINA.
 * Anche le pagine principali possono accettare props dal componente genitore (App.tsx).
 */
interface Pagina1Props {
  infoApp?: string
}

export default function Pagina1({ infoApp = 'Esempio React' }: Pagina1Props) {
  /**
   * PASSO 2: LETTURA DEI PARAMETRI NELL'URL (Query String).
   * L'hook useSearchParams() di react-router-dom restituisce un oggetto URLSearchParams.
   * Se Pagina 2 rimanda indietro una risposta del tipo '/navigazione?risposta=MessaggioRicevuto',
   * searchParams.get('risposta') ne estrae automaticamente il valore stringa decodificato!
   */
  const [searchParams] = useSearchParams()
  const risposta = searchParams.get('risposta')

  /**
   * PASSO 3: STATO LOCALE (Controlled Inputs).
   * 'nome' e 'messaggio' memorizzano il testo digitato dall'utente.
   */
  const [nome, setNome] = useState<string>('Mario')
  const [messaggio, setMessaggio] = useState<string>('Ciao dalla Pagina 1!')

  /**
   * PASSO 4: COMPOSIZIONE DINAMICA DEL LINK.
   * I parametri da inviare vengono inseriti dopo il punto interrogativo '?'.
   * NOTA IMPORTANTE: usiamo encodeURIComponent() per convertire spazi, accenti
   * e caratteri speciali in formato compatibile con gli standard degli URL (es. spazio -> %20).
   */
  const linkPersonalizzato = `/pagina2?nome=${encodeURIComponent(nome)}&messaggio=${encodeURIComponent(messaggio)}`

  return (
    /**
     * PASSO 5: UTILIZZO DEI CSS MODULES.
     * Invece di 'className="card"', usiamo 'className={styles.card}'.
     * Vite genera una classe univoca (es. Pagina1_card__1a2b3) che non entra
     * mai in conflitto con le classi di Pagina 2!
     */
    <div className={styles.card}>
      <div className={styles.badge}>Pagina 1 (Tema Viola)</div>
      <h2 className={styles.titolo}>Mittente & Home</h2>

      {/* Visualizzazione della prop 'infoApp' passata da App.tsx */}
      <p className={styles.description}>
        Applicazione: <strong>{infoApp}</strong> (ricevuto come <strong>Prop</strong> da <code>App.tsx</code>)
      </p>

      {/**
       * PASSO 6: RENDERING CONDIZIONALE DELLA RISPOSTA.
       * Se 'risposta' non è null (cioè se siamo tornati da Pagina 2 passando un parametro),
       * renderizziamo il componente SchedaMessaggio passandogli la risposta come props.
       */}
      {risposta && (
        <SchedaMessaggio
          mittente="Pagina 2"
          testo={risposta}
          etichetta="Risposta ricevuta via Link"
          tema="verde"
        />
      )}

      {/* Sezione per comporre un messaggio personalizzato */}
      <div className={styles.formSection}>
        <h3>Crea un messaggio personalizzato</h3>
        
        {/* Input controllato per il nome */}
        <div className={styles.inputGroup}>
          <label htmlFor="nome">Il tuo nome:</label>
          <input
            id="nome"
            type="text"
            value={nome}
            onChange={(e) => setNome(e.target.value)}
            placeholder="Es. Mario Rossi"
          />
        </div>

        {/* Input controllato per il messaggio */}
        <div className={styles.inputGroup}>
          <label htmlFor="messaggio">Messaggio:</label>
          <input
            id="messaggio"
            type="text"
            value={messaggio}
            onChange={(e) => setMessaggio(e.target.value)}
            placeholder="Scrivi qualcosa..."
          />
        </div>

        {/**
         * PASSO 7: DIMOSTRAZIONE ANTEPRIMA LIVE TRAMITE PROPS.
         * Mentre l'utente digita, lo state locale cambia e il componente SchedaMessaggio
         * riceve i nuovi valori via Props, aggiornandosi in tempo reale!
         */}
        <h4>Anteprima in tempo reale (Componente Figlio con Props):</h4>
        <SchedaMessaggio
          mittente={nome || 'Anonimo'}
          testo={messaggio || 'Nessun testo inserito'}
          etichetta="Anteprima Props"
          tema="viola"
        />

        {/* Anteprima visiva dell'URL generato per comprendere come viaggiano i dati */}
        <div className={styles.urlPreview}>
          <span>Link generato dinamicamente:</span>
          <code>{linkPersonalizzato}</code>
        </div>

        {/**
         * PASSO 8: IL LINK DI NAVIGAZIONE.
         * Quando l'utente clicca, react-router-dom naviga verso /pagina2 portando
         * i parametri nella query string, senza MAI ricaricare l'intera pagina!
         */}
        <Link to={linkPersonalizzato} className={styles.btn}>
          👉 Vai a Pagina 2 passando i dati nel Link
        </Link>
      </div>

      {/* Esempi di link preconfigurati per test rapidi */}
      <div className={styles.quickLinks}>
        <h3>Oppure clicca su un link d'esempio rapido:</h3>
        <div className={styles.linksGrid}>
          <Link
            to="/pagina2?nome=Docente&messaggio=Promemoria:%20compito%20in%20classe%20venerdì!"
            className={styles.quickLinkItem}
          >
            <strong>Avviso Docente</strong>
            <span>?nome=Docente&messaggio=Compito...</span>
          </Link>

          <Link
            to="/pagina2?nome=Luigi&messaggio=Ci%20vediamo%20dopo%20scuola%20in%20biblioteca?"
            className={styles.quickLinkItem}
          >
            <strong>Messaggio di Luigi</strong>
            <span>?nome=Luigi&messaggio=Ci vediamo...</span>
          </Link>

          <Link
            to="/pagina2?nome=Anonimo&messaggio=La%20comunicazione%20via%20link%20funziona%20alla%20grande!"
            className={styles.quickLinkItem}
          >
            <strong>Messaggio Anonimo</strong>
            <span>?nome=Anonimo&messaggio=Funziona...</span>
          </Link>
        </div>
      </div>
    </div>
  )
}
