// ============================================================================
// FILE: src/pages/LezioneTaskManager.tsx
// SCOPO DIDATTICO: Esercitazione Completa Task Manager.
// Spiega le tre operazioni fondamentali di manipolazione immutabile dello stato:
// 1. Aggiunta con Spread Operator: [...prev, nuovo]
// 2. Modifica/Toggle con Array Map + Object Spread: prev.map(...)
// 3. Eliminazione con Array Filter: prev.filter(...)
// ============================================================================

import { useState } from 'react'
import type { Task } from '../types/task'
import { TaskItem } from '../components/TaskItem'

// Dati iniziali per permettere alla classe di visualizzare subito elementi all'avvio
const TASK_INIZIALI: Task[] = [
  { id: 1, testo: 'Studiare la sintassi JSX e le differenze con HTML', completato: true, priorita: 'Alta' },
  { id: 2, testo: 'Comprendere il pattern Lifting State Up', completato: false, priorita: 'Alta' },
  { id: 3, testo: 'Eseguire push su repository GitHub', completato: false, priorita: 'Media' },
  { id: 4, testo: 'Rivedere la documentazione di Vite', completato: false, priorita: 'Bassa' },
]

export default function LezioneTaskManager() {
  /**
   * PASSO 1: STATO PRINCIPALE (Array di oggetti Task).
   * 'tasks' contiene la lista dei compiti.
   * 'setTasks' è la funzione che usiamo per aggiornare la lista.
   */
  const [tasks, setTasks] = useState<Task[]>(TASK_INIZIALI)

  /**
   * STATI DEL FORM: Gestiscono i campi controllati dell'input utente.
   */
  const [nuovoTesto, setNuovoTesto] = useState<string>('')
  const [priorita, setPriorita] = useState<'Bassa' | 'Media' | 'Alta'>('Media')

  /**
   * =========================================================================
   * OPERAZIONE 1: AGGIUNTA IMMUTABILE (Spread Operator [...prev, nuovo])
   * =========================================================================
   * REGOLA FONDAMENTALE DI REACT:
   * MAI usare 'tasks.push(nuovoTask)'. La funzione push muta l'array originale in memoria.
   * Poiché il riferimento dell'array non cambierebbe, React NON rileverebbe la modifica
   * e NON aggiornerebbe il DOM (nessun re-render visibile)!
   *
   * SOLUZIONE CORRETTA:
   * Creiamo un NUOVO array con lo spread operator: [...prev, nuovoTask].
   */
  const aggiungiTask = (e: React.FormEvent): void => {
    e.preventDefault() // Blocca il ricaricamento tradizionale della pagina HTML
    if (!nuovoTesto.trim()) return

    const nuovoTask: Task = {
      id: Date.now(), // Genera un id univoco basato sul timestamp corrente
      testo: nuovoTesto.trim(),
      completato: false,
      priorita: priorita,
    }

    // Aggiunta immutabile: preserva tutti i vecchi task e accoda il nuovo
    setTasks((prev) => [...prev, nuovoTask])

    // Reset del campo di testo dopo l'inserimento
    setNuovoTesto('')
  }

  /**
   * =========================================================================
   * OPERAZIONE 2: TOGGLE / MODIFICA IMMUTABILE (Array Map + Object Spread)
   * =========================================================================
   * Come modificare un singolo campo ('completato') senza toccare gli altri?
   * Con .map():
   * - Scorriamo ogni task.
   * - Se l'id corrisponde: creiamo un NUOVO oggetto copiando le proprietà (...task)
   *   e invertendo solo 'completato: !task.completato'.
   * - Se l'id non corrisponde: restituiamo il task intatto.
   */
  const toggleTask = (id: number): void => {
    setTasks((prev) =>
      prev.map((task) =>
        task.id === id ? { ...task, completato: !task.completato } : task
      )
    )
  }

  /**
   * =========================================================================
   * OPERAZIONE 3: CANCELLAZIONE IMMUTABILE (Array Filter)
   * =========================================================================
   * MAI usare 'tasks.splice(index, 1)' perché muta l'array.
   *
   * SOLUZIONE CORRETTA:
   * .filter() restituisce un NUOVO array contenente solo gli elementi
   * che soddisfano la condizione (in questo caso: tutti tranne quello con l'id selezionato).
   */
  const eliminaTask = (id: number): void => {
    setTasks((prev) => prev.filter((task) => task.id !== id))
  }

  // Calcolo derivato dallo stato (non serve un nuovo useState: si ricalcola ad ogni render!)
  const taskCompletati = tasks.filter((t) => t.completato).length

  return (
    <div style={{ maxWidth: '800px', margin: '0 auto', textAlign: 'left' }}>
      {/* Banner didattico riassuntivo */}
      <div
        style={{
          background: 'linear-gradient(135deg, #065f46, #10b981)',
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
          Esercitazione Pratica Guidata
        </span>
        <h2 style={{ margin: '0.5rem 0', color: '#fff' }}>Sistema di Gestione Task Reattivo</h2>
        <p style={{ margin: 0, opacity: 0.9, lineHeight: 1.5 }}>
          Questa schermata implementa le tre operazioni fondamentali di manipolazione dello stato di un array in React:{' '}
          <strong>Aggiunta (Spread)</strong>, <strong>Toggle (Map)</strong> e <strong>Cancellazione (Filter)</strong>,
          rispettando la regola di immutabilità di React.
        </p>
      </div>

      {/* Form di inserimento controllato */}
      <form
        onSubmit={aggiungiTask}
        style={{
          backgroundColor: '#fff',
          border: '1px solid #e2e8f0',
          borderRadius: '12px',
          padding: '1.25rem',
          marginBottom: '1.5rem',
          boxShadow: '0 4px 6px -1px rgba(0,0,0,0.05)',
        }}
      >
        <h3 style={{ margin: '0 0 1rem 0', color: '#0f172a' }}>➕ Aggiungi Nuovo Task</h3>

        <div style={{ display: 'flex', gap: '0.75rem', flexWrap: 'wrap' }}>
          {/* Input controllato: il valore riflette 'nuovoTesto' e ogni tasto aggiorna lo stato */}
          <input
            type="text"
            value={nuovoTesto}
            onChange={(e) => setNuovoTesto(e.target.value)}
            placeholder="Cosa devi fare? (es. Preparare le slide di React)"
            style={{
              flex: '1 1 300px',
              padding: '0.75rem 1rem',
              border: '1px solid #cbd5e1',
              borderRadius: '8px',
              fontSize: '1rem',
              outline: 'none',
            }}
          />

          {/* Select tipizzata per l'Union Type */}
          <select
            value={priorita}
            onChange={(e) => setPriorita(e.target.value as 'Bassa' | 'Media' | 'Alta')}
            style={{
              padding: '0.75rem 1rem',
              border: '1px solid #cbd5e1',
              borderRadius: '8px',
              fontSize: '0.95rem',
              backgroundColor: '#f8fafc',
              cursor: 'pointer',
            }}
          >
            <option value="Bassa">Priorità: Bassa</option>
            <option value="Media">Priorità: Media</option>
            <option value="Alta">Priorità: Alta</option>
          </select>

          <button
            type="submit"
            style={{
              backgroundColor: '#10b981',
              color: '#fff',
              border: 'none',
              borderRadius: '8px',
              padding: '0.75rem 1.5rem',
              fontWeight: 600,
              fontSize: '1rem',
              cursor: 'pointer',
            }}
          >
            Aggiungi
          </button>
        </div>
      </form>

      {/* Contatore dinamico */}
      <div
        style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          marginBottom: '1rem',
          padding: '0.5rem 0',
        }}
      >
        <h3 style={{ margin: 0, color: '#1e293b' }}>
          📋 Elenco Task ({tasks.length})
        </h3>
        <span style={{ fontSize: '0.95rem', color: '#047857', fontWeight: 600 }}>
          Completati: {taskCompletati} su {tasks.length}
        </span>
      </div>

      {/**
       * Rendering della Lista:
       * Usiamo .map() per trasformare ogni oggetto Task in un componente <TaskItem />.
       * IMPORTANTE: La prop 'key={task.id}' è obbligatoria in React per permettere al
       * Virtual DOM di identificare quali elementi sono stati aggiunti, rimossi o modificati.
       */}
      {tasks.length === 0 ? (
        <div
          style={{
            textAlign: 'center',
            padding: '2.5rem',
            backgroundColor: '#f8fafc',
            borderRadius: '12px',
            border: '2px dashed #cbd5e1',
            color: '#64748b',
          }}
        >
          <p style={{ margin: 0, fontSize: '1.1rem' }}>🎉 Nessun task presente! Aggiungine uno con il form sopra.</p>
        </div>
      ) : (
        <ul style={{ listStyle: 'none', padding: 0, margin: 0 }}>
          {tasks.map((task) => (
            <TaskItem
              key={task.id}
              task={task}
              onToggle={toggleTask}
              onDelete={eliminaTask}
            />
          ))}
        </ul>
      )}

      {/* Sintesi delle 3 operazioni per proiettore / appunti studenti */}
      <div
        style={{
          marginTop: '2rem',
          backgroundColor: '#f0fdf4',
          border: '1px solid #bbf7d0',
          borderRadius: '12px',
          padding: '1.25rem',
        }}
      >
        <h4 style={{ margin: '0 0 0.75rem 0', color: '#166534' }}>
          💡 Sintesi Didattica: Le 3 Operazioni Immutabili su Array
        </h4>
        <div style={{ fontSize: '0.88rem', color: '#14532d', lineHeight: 1.6 }}>
          <p style={{ margin: '0.4rem 0' }}>
            <strong>1. Aggiunta:</strong> <code>setTasks((prev) =&gt; [...prev, nuovoElemento])</code> — Crea un nuovo array preservando gli elementi precedenti.
          </p>
          <p style={{ margin: '0.4rem 0' }}>
            <strong>2. Modifica/Toggle:</strong> <code>setTasks((prev) =&gt; prev.map(t =&gt; t.id === id ? &#123; ...t, completato: !t.completato &#125; : t))</code> — Sostituisce solo l'oggetto target tramite spread.
          </p>
          <p style={{ margin: '0.4rem 0' }}>
            <strong>3. Eliminazione:</strong> <code>setTasks((prev) =&gt; prev.filter(t =&gt; t.id !== id))</code> — Restituisce un nuovo array escludendo l'id selezionato.
          </p>
        </div>
      </div>
    </div>
  )
}
