// ============================================================================
// FILE: src/pages/LezioneJSXuseState.tsx
// SCOPO DIDATTICO: Modulo 1 - Fondamenti di JSX e Meccanismo dell'Hook useState.
//
// CONCETTI CHIAVE ILLUSTRATI:
// 1. JSX non è HTML: è zucchero sintattico compilato in React.createElement().
// 2. Differenze sintattiche: className, htmlFor, tag chiusi obbligatori, {}.
// 3. Perché una variabile normale (let) non provoca il re-render del DOM.
// 4. Come useState attiva il ciclo: Interazione -> Setter -> Virtual DOM -> Re-render.
// 5. Aggiornamento funzionale sicuro: setCount((prev) => prev + 1).
// ============================================================================

import { useState } from 'react'

export default function LezioneJSXuseState() {
  /**
   * PASSO 1: ESPRESSIONI DINAMICHE IN JSX.
   * In JSX possiamo inserire qualsiasi espressione JavaScript valida
   * all'interno di parentesi graffe {}.
   */
  const nomeStudente: string = 'Mario Rossi'
  const dataOggi: string = new Date().toLocaleDateString('it-IT')

  /**
   * PASSO 2: DICHIARAZIONE DELL'HOOK useState.
   * const [valoreStato, setValoreStato] = useState<Tipo>(valoreIniziale);
   * - conteggio: il valore corrente (sola lettura per noi).
   * - setConteggio: funzione setter per richiedere un re-render con un nuovo valore.
   */
  const [conteggio, setConteggio] = useState<number>(0)

  /**
   * ESPERIMENTO DIDATTICO: Variabile classica 'let'.
   * Usata per dimostrare agli studenti perché senza useState l'interfaccia rimane ferma.
   */
  let variabileNormale: number = 0

  const incrementaNormale = () => {
    variabileNormale += 1
    // La variabile in memoria aumenta, ma React NON riceve alcuna notifica.
    // Il Virtual DOM non si aggiorna e il browser rimane a 0!
    console.log('Variabile normale incrementata:', variabileNormale, '(il DOM non si aggiorna!)')
  }

  /**
   * PASSO 3: AGGIORNAMENTO FUNZIONALE (Safe Functional Update).
   * Invece di 'setConteggio(conteggio + 1)', usiamo la callback '(prev) => prev + 1'.
   * Questo garantisce il valore più recente anche in caso di aggiornamenti multipli o asincroni.
   */
  const incrementaFunzionale = () => {
    setConteggio((prev) => prev + 1)
  }

  const resettaConteggio = () => {
    setConteggio(0)
  }

  return (
    <div style={{ maxWidth: '850px', margin: '0 auto', textAlign: 'left' }}>
      {/* Banner didattico introduttivo */}
      <div
        style={{
          background: 'linear-gradient(135deg, #7c3aed, #a855f7)',
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
          Modulo 1: Teoria & Fondamenti
        </span>
        <h2 style={{ margin: '0.5rem 0', color: '#fff' }}>Sintassi JSX & Meccanismo di useState</h2>
        <p style={{ margin: 0, opacity: 0.9, lineHeight: 1.5 }}>
          Confronto tra HTML classico e JSX, e dimostrazione pratica del perché le variabili standard non aggiornano il DOM
          mentre <code>useState</code> pilota il ciclo di re-render del Virtual DOM.
        </p>
      </div>

      {/* SEZIONE 1: CONFRONTO SINTATTICO HTML VS JSX */}
      <div
        style={{
          backgroundColor: '#fff',
          border: '1px solid #e2e8f0',
          borderRadius: '12px',
          padding: '1.5rem',
          marginBottom: '2rem',
          boxShadow: '0 2px 4px rgba(0,0,0,0.04)',
        }}
      >
        <h3 style={{ marginTop: 0, color: '#6d28d9' }}>1. Le differenze chiave tra HTML e JSX</h3>
        <p style={{ color: '#475569' }}>
          JSX non è una stringa e non è HTML reale: è zucchero sintattico che Vite/Babel compila in chiamate <code>React.createElement()</code>.
        </p>

        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem', marginTop: '1rem' }}>
          {/* Card HTML */}
          <div style={{ background: '#f8fafc', padding: '1rem', borderRadius: '8px', border: '1px solid #cbd5e1' }}>
            <h4 style={{ margin: '0 0 0.5rem 0', color: '#dc2626' }}>❌ HTML Classico</h4>
            <pre style={{ fontSize: '0.85rem', margin: 0, overflowX: 'auto', background: '#fff', padding: '0.5rem' }}>
{`<div class="card">
  <!-- 'for' è una parola riservata JS! -->
  <label for="nome">Nome</label>
  <!-- I tag void non chiusi sono tollerati -->
  <input type="text" id="nome">
  <p>Benvenuto, Mario!</p>
</div>`}
            </pre>
          </div>

          {/* Card JSX */}
          <div style={{ background: '#faf5ff', padding: '1rem', borderRadius: '8px', border: '1px solid #d8b4fe' }}>
            <h4 style={{ margin: '0 0 0.5rem 0', color: '#7c3aed' }}>✅ Equivalente JSX / TSX</h4>
            <pre style={{ fontSize: '0.85rem', margin: 0, overflowX: 'auto', background: '#fff', padding: '0.5rem' }}>
{`<div className="card">
  <!-- In JSX si usa 'htmlFor' -->
  <label htmlFor="nome">Nome</label>
  <!-- Tag auto-chiuso obbligatorio: /> -->
  <input type="text" id="nome" />
  <!-- Variabile dinamica tra { } -->
  <p>Benvenuto, {nomeStudente}!</p>
</div>`}
            </pre>
          </div>
        </div>

        {/* Dimostrazione live */}
        <div style={{ marginTop: '1rem', padding: '0.75rem', background: '#f5f3ff', borderRadius: '8px' }}>
          <strong>Dimostrazione live JSX:</strong> Benvenuto <strong>{nomeStudente}</strong>! Oggi è il <strong>{dataOggi}</strong>.
        </div>
      </div>

      {/* SEZIONE 2: ESPERIMENTO DIDATTICO LET VS USESTATE */}
      <div
        style={{
          backgroundColor: '#fff',
          border: '1px solid #e2e8f0',
          borderRadius: '12px',
          padding: '1.5rem',
          boxShadow: '0 2px 4px rgba(0,0,0,0.04)',
        }}
      >
        <h3 style={{ marginTop: 0, color: '#6d28d9' }}>2. Perché le normali variabili JS non bastano?</h3>
        <p style={{ color: '#475569', lineHeight: 1.5 }}>
          Una variabile dichiarata con <code>let</code> o <code>const</code> cambia in memoria ma{' '}
          <strong>non notifica React</strong> e non causa il re-render del DOM. Solo <code>useState</code> aggiorna il Virtual DOM!
        </p>

        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1.5rem', marginTop: '1.5rem' }}>
          {/* Card Esperimento A: let */}
          <div
            style={{
              padding: '1.25rem',
              border: '2px dashed #cbd5e1',
              borderRadius: '10px',
              backgroundColor: '#f8fafc',
              textAlign: 'center',
            }}
          >
            <h4 style={{ margin: '0 0 0.5rem 0', color: '#64748b' }}>Test con <code>let x = 0</code></h4>
            <p style={{ fontSize: '2rem', fontWeight: 'bold', margin: '0.5rem 0', color: '#94a3b8' }}>
              {variabileNormale}
            </p>
            <button
              type="button"
              onClick={incrementaNormale}
              style={{
                padding: '0.6rem 1rem',
                backgroundColor: '#94a3b8',
                color: '#fff',
                border: 'none',
                borderRadius: '6px',
                cursor: 'pointer',
              }}
            >
              Incrementa (let)
            </button>
            <p style={{ fontSize: '0.75rem', color: '#ef4444', marginTop: '0.75rem' }}>
              ⚠️ Clicca: a video rimane 0 perché React non fa il re-render!
            </p>
          </div>

          {/* Card Esperimento B: useState */}
          <div
            style={{
              padding: '1.25rem',
              border: '2px solid #7c3aed',
              borderRadius: '10px',
              backgroundColor: '#faf5ff',
              textAlign: 'center',
            }}
          >
            <h4 style={{ margin: '0 0 0.5rem 0', color: '#7c3aed' }}>Test con <code>useState(0)</code></h4>
            <p style={{ fontSize: '2rem', fontWeight: 'bold', margin: '0.5rem 0', color: '#7c3aed' }}>
              {conteggio}
            </p>
            <div style={{ display: 'flex', gap: '0.5rem', justifyContent: 'center' }}>
              <button
                type="button"
                onClick={incrementaFunzionale}
                style={{
                  padding: '0.6rem 1.2rem',
                  backgroundColor: '#7c3aed',
                  color: '#fff',
                  border: 'none',
                  borderRadius: '6px',
                  cursor: 'pointer',
                  fontWeight: 600,
                }}
              >
                +1 (setConteggio)
              </button>
              <button
                type="button"
                onClick={resettaConteggio}
                style={{
                  padding: '0.6rem 0.8rem',
                  backgroundColor: '#e9d5ff',
                  color: '#6d28d9',
                  border: 'none',
                  borderRadius: '6px',
                  cursor: 'pointer',
                }}
              >
                Reset
              </button>
            </div>
            <p style={{ fontSize: '0.75rem', color: '#059669', marginTop: '0.75rem', fontWeight: 600 }}>
              ✅ Reattivo: React rileva il setter e aggiorna il DOM istantaneamente!
            </p>
          </div>
        </div>
      </div>
    </div>
  )
}
