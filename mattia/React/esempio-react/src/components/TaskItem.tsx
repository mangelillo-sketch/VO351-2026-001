// ============================================================================
// FILE: src/components/TaskItem.tsx
// SCOPO DIDATTICO: Componente funzionale "Presentational" (Figlio).
// Riceve i dati dal genitore tramite Props e notifica le azioni tramite Callback.
// ============================================================================

import type { Task } from '../types/task'

/**
 * PASSO 1: Definizione del "contratto" delle Props con TypeScript.
 * Spiega al compilatore e a chi usa il componente cosa deve essere passato:
 * - task: l'oggetto contenente i dati del singolo compito (id, testo, completato, priorità)
 * - onToggle: funzione callback che il figlio invoca quando l'utente clicca sulla checkbox
 * - onDelete: funzione callback che il figlio invoca quando l'utente clicca su "Elimina"
 */
export interface TaskItemProps {
  task: Task
  onToggle: (id: number) => void
  onDelete: (id: number) => void
}

/**
 * PASSO 2: Componente Funzionale.
 * Utilizza la DESTRUTTURAZIONE degli argomenti ({ task, onToggle, onDelete })
 * per estrarre direttamente le proprietà dall'oggetto props.
 */
export function TaskItem({ task, onToggle, onDelete }: TaskItemProps) {
  // Configurazione grafica basata sull'Union Type 'Bassa' | 'Media' | 'Alta'.
  // Questo garantisce che ad ogni priorità corrisponda una palette colore corretta.
  const coloriPriorita = {
    Bassa: { bg: '#e0f2fe', text: '#0369a1', border: '#bae6fd' },
    Media: { bg: '#fef3c7', text: '#b45309', border: '#fde68a' },
    Alta: { bg: '#fee2e2', text: '#b91c1c', border: '#fecaca' },
  }

  const badgeStile = coloriPriorita[task.priorita]

  return (
    <li
      style={{
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        padding: '0.85rem 1rem',
        // STILE CONDIZIONALE: se completato, sfondo leggermente opacizzato
        backgroundColor: task.completato ? '#f8fafc' : '#ffffff',
        border: '1px solid #e2e8f0',
        borderRadius: '8px',
        marginBottom: '0.6rem',
        boxShadow: '0 1px 3px rgba(0,0,0,0.05)',
        opacity: task.completato ? 0.75 : 1,
        transition: 'all 0.2s',
      }}
    >
      <div style={{ display: 'flex', alignItems: 'center', gap: '0.85rem', flex: 1 }}>
        {/*
          PASSO 3: GESTIONE EVENTI (Lifting State Up).
          Quando l'utente clicca la checkbox, scatta l'evento 'onChange'.
          Il componente figlio NON modifica lo stato (le props sono immutabili!).
          Invece, CHIAMA la callback del padre: onToggle(task.id).
        */}
        <input
          type="checkbox"
          checked={task.completato}
          onChange={() => onToggle(task.id)}
          style={{ width: '18px', height: '18px', cursor: 'pointer' }}
          id={`task-${task.id}`}
        />

        {/*
          PASSO 4: RENDERING CONDIZIONALE DELLO STILE.
          Se task.completato === true, applichiamo la linea barrata ('line-through').
          In JSX usiamo 'htmlFor' al posto dell'attributo HTML standard 'for'.
        */}
        <label
          htmlFor={`task-${task.id}`}
          style={{
            cursor: 'pointer',
            fontSize: '1rem',
            color: task.completato ? '#94a3b8' : '#1e293b',
            textDecoration: task.completato ? 'line-through' : 'none',
            fontWeight: task.completato ? 400 : 500,
          }}
        >
          {task.testo}
        </label>

        {/* Badge che visualizza la priorità tipizzata */}
        <span
          style={{
            fontSize: '0.75rem',
            fontWeight: 700,
            padding: '0.2rem 0.5rem',
            borderRadius: '999px',
            backgroundColor: badgeStile.bg,
            color: badgeStile.text,
            border: `1px solid ${badgeStile.border}`,
          }}
        >
          {task.priorita}
        </span>
      </div>

      {/*
        PASSO 5: BOTTONE ELIMINAZIONE.
        Anche in questo caso invochiamo una callback: onDelete(task.id).
        Sarà il componente genitore a filtrare l'array ed aggiornare lo stato.
      */}
      <button
        type="button"
        onClick={() => onDelete(task.id)}
        style={{
          backgroundColor: 'transparent',
          color: '#ef4444',
          border: '1px solid #fca5a5',
          borderRadius: '6px',
          padding: '0.35rem 0.65rem',
          cursor: 'pointer',
          fontSize: '0.85rem',
          fontWeight: 600,
          transition: 'all 0.15s',
        }}
        title="Elimina task"
      >
        🗑️ Elimina
      </button>
    </li>
  )
}
