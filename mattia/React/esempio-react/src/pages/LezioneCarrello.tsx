// ============================================================================
// FILE: src/pages/LezioneCarrello.tsx
// SCOPO DIDATTICO: Dimostrazione del Pattern "Lifting State Up" (Sollevamento dello Stato).
//
// PROBLEMA:
// Ogni CardProdotto possiede uno stato locale (la quantità selezionata), ma l'informazione
// dell'acquisto deve finire nel Carrello Globale (posseduto dal genitore).
//
// SOLUZIONE:
// 1. Il genitore (LezioneCarrello) possiede lo stato 'carrello'.
// 2. Il genitore passa ai figli una callback ('onAcquisto').
// 3. Il figlio invoca la callback quando l'utente clicca il bottone d'acquisto.
// 4. Il genitore riceve i dati e aggiorna il proprio stato.
// FLUSSO: "I dati scendono tramite Props, le azioni salgono tramite Callback".
// ============================================================================

import { useState } from 'react'
import { CardProdotto } from '../components/CardProdotto'
import type { ElementoCarrello } from '../types/prodotto'

// Catalogo prodotti statico di partenza
const CATALOGO_INIZIALE = [
  { id: 1, titolo: 'Vaso Ceramica Beige', prezzo: 48.0, disponibile: true },
  { id: 2, titolo: 'Lampada da Tavolo Minimal', prezzo: 79.5, disponibile: true },
  { id: 3, titolo: 'Tazza Artigianale', prezzo: 16.0, disponibile: false }, // Dimostra il conditional rendering
  { id: 4, titolo: 'Quaderno con Copertina Rigida', prezzo: 12.5, disponibile: true },
]

export default function LezioneCarrello() {
  /**
   * PASSO 1: STATO DEL CARRELLO (Lifting State Up).
   * Lo stato del carrello risiede qui, al livello più alto comune a tutti i prodotti.
   */
  const [carrello, setCarrello] = useState<ElementoCarrello[]>([])

  /**
   * PASSO 2: CALLBACK PASSATA AI FIGLI.
   * Quando CardProdotto invoca 'onAcquisto', esegue questa funzione.
   * Riceve il nome e la quantità e aggiorna lo stato in modo immutabile.
   */
  const gestisciAcquisto = (nomeProdotto: string, quantitaAcquistata: number): void => {
    // Cerchiamo le informazioni di prezzo per questo prodotto
    const prodottoInfo = CATALOGO_INIZIALE.find((p) => p.titolo === nomeProdotto)
    const prezzo = prodottoInfo ? prodottoInfo.prezzo : 0

    // Creiamo il nuovo oggetto da aggiungere al carrello
    const nuovoElemento: ElementoCarrello = {
      prodotto: nomeProdotto,
      quantitaTotale: quantitaAcquistata,
      prezzoUnitario: prezzo,
    }

    /**
     * AGGIORNAMENTO IMMUTABILE DELLO STATO:
     * Usiamo lo spread operator per creare un nuovo array contenente
     * tutti i precedenti elementi (...prevCarrello) più il nuovoElemento.
     */
    setCarrello((prevCarrello) => [...prevCarrello, nuovoElemento])
  }

  /**
   * CALCOLO DEL TOTALE (Stato Derivato):
   * Invece di creare un ulteriore useState per il totale, lo calcoliamo
   * direttamente con .reduce() durante il render.
   */
  const totaleEuro = carrello.reduce(
    (acc, item) => acc + item.prezzoUnitario * item.quantitaTotale,
    0
  )

  const svuotaCarrello = (): void => {
    setCarrello([])
  }

  return (
    <div style={{ maxWidth: '900px', margin: '0 auto', textAlign: 'left' }}>
      {/* Banner didattico */}
      <div
        style={{
          background: 'linear-gradient(135deg, #1e3a8a, #3b82f6)',
          color: '#fff',
          padding: '1.5rem',
          borderRadius: '12px',
          marginBottom: '2rem',
        }}
      >
        <span
          style={{
            backgroundColor: 'rgba(255,255,255,0.2)',
            padding: '0.2rem 0.6rem',
            borderRadius: '4px',
            fontSize: '0.8rem',
            fontWeight: 'bold',
            textTransform: 'uppercase',
          }}
        >
          Esempio 2: Catalogo & Carrello
        </span>
        <h2 style={{ margin: '0.5rem 0', color: '#fff' }}>Pattern Lifting State Up & Props Callback</h2>
        <p style={{ margin: 0, opacity: 0.9, lineHeight: 1.5 }}>
          Ogni <strong>CardProdotto</strong> (figlio) gestisce la propria quantità locale con <code>useState</code>.
          Quando clicchi su "Aggiungi al Carrello", invoca la callback <code>onAcquisto</code> passando i dati
          al genitore, che aggiorna lo stato globale del carrello in modo <strong>immutabile</strong>.
        </p>
      </div>

      <div style={{ display: 'grid', gridTemplateColumns: '1fr 340px', gap: '2rem' }}>
        {/* Colonna Sinistra: Catalogo Prodotti */}
        <div>
          <h3 style={{ marginTop: 0, color: '#0f172a' }}>📦 Prodotti Disponibili</h3>
          
          {/**
           * Rendering del catalogo con .map():
           * Passiamo a ogni CardProdotto i suoi dati e la callback gestisciAcquisto.
           */}
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(240px, 1fr))', gap: '1rem' }}>
            {CATALOGO_INIZIALE.map((prod) => (
              <CardProdotto
                key={prod.id}
                id={prod.id}
                titolo={prod.titolo}
                prezzo={prod.prezzo}
                disponibile={prod.disponibile}
                onAcquisto={gestisciAcquisto} // <- Callback Lifting State Up
              />
            ))}
          </div>
        </div>

        {/* Colonna Destra: Carrello Globale (CartSummary) */}
        <div>
          <div
            style={{
              backgroundColor: '#fff',
              border: '2px solid #3b82f6',
              borderRadius: '12px',
              padding: '1.25rem',
              boxShadow: '0 4px 12px rgba(59, 130, 246, 0.1)',
              position: 'sticky',
              top: '1rem',
            }}
          >
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <h3 style={{ margin: 0, color: '#1e3a8a' }}>🛒 Carrello ({carrello.length})</h3>
              {carrello.length > 0 && (
                <button
                  type="button"
                  onClick={svuotaCarrello}
                  style={{
                    fontSize: '0.75rem',
                    color: '#ef4444',
                    background: 'none',
                    border: 'none',
                    cursor: 'pointer',
                    textDecoration: 'underline',
                  }}
                >
                  Svuota
                </button>
              )}
            </div>

            {/* Rendering condizionale se il carrello è vuoto */}
            {carrello.length === 0 ? (
              <p style={{ color: '#64748b', fontSize: '0.95rem', margin: '1.5rem 0', textAlign: 'center' }}>
                Il carrello è vuoto.<br />
                Aggiungi prodotti dal catalogo a sinistra!
              </p>
            ) : (
              <div style={{ margin: '1rem 0' }}>
                <ul style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                  {carrello.map((item, index) => (
                    <li
                      key={index}
                      style={{
                        padding: '0.5rem 0',
                        borderBottom: '1px solid #f1f5f9',
                        display: 'flex',
                        justifyContent: 'space-between',
                        fontSize: '0.9rem',
                      }}
                    >
                      <div>
                        <strong>{item.prodotto}</strong>
                        <div style={{ color: '#64748b', fontSize: '0.8rem' }}>
                          Quantità: {item.quantitaTotale} × €{item.prezzoUnitario.toFixed(2)}
                        </div>
                      </div>
                      <span style={{ fontWeight: 600, color: '#1e293b' }}>
                        €{(item.quantitaTotale * item.prezzoUnitario).toFixed(2)}
                      </span>
                    </li>
                  ))}
                </ul>

                <div
                  style={{
                    borderTop: '2px solid #e2e8f0',
                    marginTop: '1rem',
                    paddingTop: '0.75rem',
                    display: 'flex',
                    justifyContent: 'space-between',
                    fontSize: '1.1rem',
                  }}
                >
                  <strong>Totale:</strong>
                  <strong style={{ color: '#2563eb' }}>€{totaleEuro.toFixed(2)}</strong>
                </div>
              </div>
            )}

            {/* Riepilogo didattico del flusso dati per gli studenti */}
            <div
              style={{
                backgroundColor: '#f8fafc',
                padding: '0.75rem',
                borderRadius: '8px',
                fontSize: '0.8rem',
                color: '#475569',
                marginTop: '1rem',
                borderLeft: '3px solid #3b82f6',
              }}
            >
              <strong>Flusso Dati:</strong><br />
              1. <em>Props discendono</em>: da <code>App</code> a <code>CardProdotto</code><br />
              2. <em>Azioni salgono</em>: <code>onAcquisto(titolo, qty)</code><br />
              3. <em>Aggiornamento immutabile</em>: <code>[...prev, nuovo]</code>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
