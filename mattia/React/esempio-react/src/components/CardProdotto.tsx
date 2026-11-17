// ============================================================================
// FILE: src/components/CardProdotto.tsx
// SCOPO DIDATTICO: Dimostrazione di:
// 1. Props con TypeScript e destrutturazione
// 2. Stato locale con useState<number>
// 3. Forma funzionale del setter per incrementi sicuri
// 4. Conditional Rendering (operatore ternario ? :)
// 5. Pattern Lifting State Up (inviare i dati al carrello del padre)
// ============================================================================

import { useState } from 'react'

/**
 * Contratto dei dati che questo componente DEVE ricevere dal genitore.
 * Nota: 'onAcquisto' è una funzione di Callback (Lifting State Up).
 */
export interface CardProdottoProps {
  id: number
  titolo: string
  prezzo: number
  disponibile: boolean
  onAcquisto: (nomeProdotto: string, quantita: number) => void
}

export function CardProdotto({
  titolo,
  prezzo,
  disponibile,
  onAcquisto,
}: CardProdottoProps) {
  /**
   * PASSO 1: STATO LOCALE (useState).
   * La quantità è uno stato "privato" di questa specifica card.
   * Inizializzato a 1 tramite useState<number>(1).
   */
  const [quantita, setQuantita] = useState<number>(1)

  /**
   * PASSO 2: AGGIORNAMENTO FUNZIONALE (Best Practice).
   * Quando il nuovo valore dipende dal valore precedente, si passa una funzione freccia:
   * setQuantita((prev) => prev + 1)
   * Questo garantisce che 'prev' sia SEMPRE il valore più recente in memoria.
   */
  const incrementaQuantita = (): void => {
    setQuantita((prev) => prev + 1)
  }

  /**
   * PASSO 3: DECREMENTO CON VINCOLO LOGICO.
   * Evita quantità negative o pari a zero (minimo consentito: 1).
   */
  const decrementaQuantita = (): void => {
    if (quantita > 1) {
      setQuantita((prev) => prev - 1)
    }
  }

  return (
    <div
      style={{
        border: '1px solid #e2e8f0',
        borderRadius: '12px',
        padding: '1.25rem',
        backgroundColor: '#fff',
        boxShadow: '0 4px 6px -1px rgba(0,0,0,0.05)',
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'space-between',
      }}
    >
      <div>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'baseline' }}>
          <h3 style={{ margin: '0 0 0.5rem 0', color: '#1e293b' }}>{titolo}</h3>
          <span style={{ fontSize: '1.1rem', fontWeight: 700, color: '#2563eb' }}>
            €{prezzo.toFixed(2)}
          </span>
        </div>
      </div>

      {/**
       * PASSO 4: CONDITIONAL RENDERING (Rendering Condizionale).
       * Utilizziamo l'operatore ternario:
       *   disponibile ? (Mostra controlli e acquisto) : (Mostra avviso esaurito)
       * In React, solo uno dei due blocchi JSX viene renderizzato nel Virtual DOM!
       */}
      {disponibile ? (
        <div style={{ marginTop: '1rem' }}>
          {/* Controlli per la modifica dello stato locale 'quantita' */}
          <div
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: '0.75rem',
              marginBottom: '0.75rem',
            }}
          >
            <span style={{ fontSize: '0.9rem', color: '#64748b' }}>Quantità:</span>
            
            <button
              type="button"
              onClick={decrementaQuantita}
              style={{
                width: '32px',
                height: '32px',
                borderRadius: '6px',
                border: '1px solid #cbd5e1',
                background: '#f8fafc',
                cursor: 'pointer',
                fontWeight: 'bold',
              }}
            >
              -
            </button>

            {/* Visualizzazione reattiva dello stato locale {quantita} */}
            <b style={{ minWidth: '24px', textAlign: 'center' }}>{quantita}</b>

            <button
              type="button"
              onClick={incrementaQuantita}
              style={{
                width: '32px',
                height: '32px',
                borderRadius: '6px',
                border: '1px solid #cbd5e1',
                background: '#f8fafc',
                cursor: 'pointer',
                fontWeight: 'bold',
              }}
            >
              +
            </button>
          </div>

          {/**
           * PASSO 5: LIFTING STATE UP.
           * Al click, il figlio invoca 'onAcquisto(titolo, quantita)'.
           * In questo modo i dati locali salgono al componente padre (App/Carrello).
           */}
          <button
            type="button"
            onClick={() => onAcquisto(titolo, quantita)}
            style={{
              width: '100%',
              backgroundColor: '#2563eb',
              color: '#fff',
              border: 'none',
              padding: '0.65rem 1rem',
              borderRadius: '8px',
              fontWeight: 600,
              cursor: 'pointer',
              transition: 'background 0.2s',
            }}
          >
            🛒 Aggiungi al Carrello (€{(prezzo * quantita).toFixed(2)})
          </button>
        </div>
      ) : (
        /* Blocco renderizzato se disponibile === false */
        <div style={{ marginTop: '1rem' }}>
          <p
            style={{
              color: '#dc3545',
              fontWeight: 'bold',
              margin: '0.5rem 0',
              padding: '0.5rem',
              backgroundColor: '#fee2e2',
              borderRadius: '6px',
              textAlign: 'center',
            }}
          >
            ⚠️ Prodotto Attualmente Esaurito
          </p>
        </div>
      )}
    </div>
  )
}
