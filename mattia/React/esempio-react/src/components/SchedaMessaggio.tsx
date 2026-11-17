// ============================================================================
// FILE: src/components/SchedaMessaggio.tsx
// SCOPO DIDATTICO: Componente Funzionale Riutilizzabile con Props e Valori di Default.
//
// CONCETTI CHIAVE ILLUSTRATI:
// 1. Interface TypeScript: Contratto formale delle proprietà in ingresso.
// 2. Destrutturazione delle Props con Valori Predefiniti (Default Props).
// 3. Stili Inline Dinamici (oggetti JS applicati alla prop 'style={{ ... }}').
// 4. Riutilizzabilità: lo stesso componente produce card con colori ed etichette diverse
//    semplicemente variando le props passate dal genitore.
// ============================================================================

/**
 * PASSO 1: DEFINIZIONE DELLE PROPS TRAMITE INTERFACE TYPESCRIPT.
 * - mittente: stringa obbligatoria
 * - testo: stringa obbligatoria contenente il messaggio
 * - etichetta?: stringa facoltativa (il '?' indica che la prop può essere omessa)
 * - tema?: union type con 3 opzioni predefinite ('viola' | 'verde' | 'arancione')
 */
export interface SchedaMessaggioProps {
  mittente: string
  testo: string
  etichetta?: string
  tema?: 'viola' | 'verde' | 'arancione'
}

/**
 * PASSO 2: DESTRUTTURAZIONE CON VALORI DI DEFAULT.
 * Notare la sintassi:
 *   etichetta = 'Messaggio'
 *   tema = 'viola'
 * Se il genitore NON passa la prop 'tema', React userà automaticamente 'viola'.
 * Se invece il genitore passa esplicitamente tema="verde", il valore esplicito
 * sovrascriverà il valore di default.
 */
export default function SchedaMessaggio({
  mittente,
  testo,
  etichetta = 'Messaggio',
  tema = 'viola',
}: SchedaMessaggioProps) {
  /**
   * PASSO 3: GESTIONE DELLO STILE DINAMICO BASATO SULLE PROPS.
   * Mappiamo ogni variante di tema a un set specifico di colori (bordo, sfondo, testo).
   * TypeScript ci protegge garantendo che le uniche chiavi possibili siano viola, verde o arancione.
   */
  const coloriTema = {
    viola: { border: '#8b5cf6', bg: 'rgba(139, 92, 246, 0.08)', text: '#6d28d9' },
    verde: { border: '#10b981', bg: 'rgba(16, 185, 129, 0.08)', text: '#059669' },
    arancione: { border: '#f59e0b', bg: 'rgba(245, 158, 11, 0.08)', text: '#b45309' },
  }

  // Seleziona la combinazione cromatica corretta in base alla prop 'tema' ricevuta
  const stileCorrente = coloriTema[tema]

  return (
    /**
     * PASSO 4: APPLICAZIONE DEGLI STILI INLINE IN JSX.
     * In JSX gli stili inline si scrivono con DOPPIE parentesi graffe:
     * - La prima coppia indica che stiamo inserendo un'espressione JavaScript.
     * - La seconda coppia definisce un oggetto letterale JavaScript con le proprietà CSS in camelCase.
     */
    <div
      style={{
        border: `2px solid ${stileCorrente.border}`,
        backgroundColor: stileCorrente.bg,
        borderRadius: '10px',
        padding: '1rem',
        margin: '1rem 0',
      }}
    >
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.5rem' }}>
        {/* Badge personalizzato con l'etichetta ricevuta come prop */}
        <span
          style={{
            fontSize: '0.75rem',
            fontWeight: 'bold',
            textTransform: 'uppercase',
            backgroundColor: stileCorrente.border,
            color: '#fff',
            padding: '0.2rem 0.6rem',
            borderRadius: '4px',
          }}
        >
          {etichetta}
        </span>

        {/* Visualizzazione del mittente ricevuto come prop */}
        <small style={{ color: '#666' }}>
          Mittente: <strong>{mittente}</strong>
        </small>
      </div>

      {/* Testo del messaggio passato dal genitore */}
      <p style={{ margin: 0, fontStyle: 'italic', fontSize: '1.05rem', color: stileCorrente.text }}>
        "{testo}"
      </p>
    </div>
  )
}
