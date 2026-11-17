// ============================================================================
// FILE: src/types/task.ts
// SCOPO DIDATTICO: Definizione del Modello Dati con TypeScript Interface.
// ============================================================================

/**
 * L'interfaccia 'Task' stabilisce la forma (contratto) che ogni oggetto compito DEVE avere.
 * Vantaggi didattici per gli studenti:
 * 1. Autocompletamento automatico nell'IDE (IntelliSense).
 * 2. Rilevamento immediato degli errori a tempo di compilazione (prima di aprire il browser).
 * 3. Documentazione chiara di come sono strutturati i dati.
 */
export interface Task {
  // Identificativo univoco (usato anche come 'key' nei cicli .map() di React)
  id: number

  // Il testo o descrizione del compito
  testo: string

  // Flag booleano che indica se il compito è stato completato
  completato: boolean

  // UNION TYPE: Limita i valori possibili esclusivamente a queste 3 stringhe.
  // È impossibile assegnare per errore altre stringhe come "Urgente" o "Normale".
  priorita: 'Bassa' | 'Media' | 'Alta'
}
