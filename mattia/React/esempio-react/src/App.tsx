// ============================================================================
// FILE: src/App.tsx
// SCOPO DIDATTICO: Componente Radice (Root Component).
//
// CONCETTI CHIAVE:
// 1. Architettura Single Page Application (SPA):
//    Un unico file HTML (index.html), tutto il routing è gestito via JavaScript senza reload.
// 2. BrowserRouter & Routes:
//    Associano i percorsi dell'URL ai rispettivi componenti React.
// 3. NavLink vs <a>:
//    - I normali tag <a href="..."> ricaricano la pagina e distruggono lo state di React.
//    - NavLink intercetta il click (senza reload) e offre la prop 'isActive' per lo stile.
// ============================================================================

import { BrowserRouter, Routes, Route, NavLink, Navigate } from 'react-router-dom'
import LezioneJSXuseState from './pages/LezioneJSXuseState'
import LezioneCarrello from './pages/LezioneCarrello'
import LezioneTaskManager from './pages/LezioneTaskManager'
import Pagina1 from './pages/Pagina1'
import Pagina2 from './pages/Pagina2'
import './assets/css/App.css'

export default function App() {
  return (
    // BrowserRouter abilita la gestione della cronologia del browser (HTML5 History API)
    <BrowserRouter>
      <div className="app-container">
        {/* Intestazione del corso / lezione */}
        <header className="app-header">
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '0.75rem', marginBottom: '0.5rem' }}>
            <span style={{ fontSize: '1.8rem' }}>⚛️</span>
            <h1 style={{ margin: 0 }}>Corso React + TypeScript — Esempi di Lezione</h1>
          </div>
          <p className="subtitle">
            JSX, Componenti Funzionali, Props tipizzate, Hook useState e Lifting State Up
          </p>

          {/**
           * Barra di Navigazione:
           * Usiamo NavLink: la funzione ({ isActive }) => ... permette di aggiungere
           * la classe 'active' al pulsante attualmente selezionato.
           */}
          <nav className="nav-bar" style={{ flexWrap: 'wrap', justifyContent: 'center', gap: '0.5rem' }}>
            <NavLink
              to="/fondamenti"
              className={({ isActive }) => (isActive ? 'nav-link active' : 'nav-link')}
            >
              💡 1. JSX & useState
            </NavLink>

            <NavLink
              to="/carrello"
              className={({ isActive }) => (isActive ? 'nav-link active' : 'nav-link')}
            >
              🛒 2. Catalogo & Lifting State
            </NavLink>

            <NavLink
              to="/task-manager"
              className={({ isActive }) => (isActive ? 'nav-link active' : 'nav-link')}
            >
              ✅ 3. Esercitazione Task Manager
            </NavLink>

            <NavLink
              to="/navigazione"
              className={({ isActive }) => (isActive ? 'nav-link active' : 'nav-link')}
            >
              🔗 4. Navigazione & Link
            </NavLink>
          </nav>
        </header>

        {/**
         * Area Contenuto:
         * Routes valuta l'URL corrente e renderizza SOLO il componente corrispondente
         */}
        <main className="main-content">
          <Routes>
            {/* Redirect automatico dalla homepage alla prima lezione */}
            <Route path="/" element={<Navigate to="/fondamenti" replace />} />
            
            {/* Modulo 1: Teoria JSX e useState */}
            <Route path="/fondamenti" element={<LezioneJSXuseState />} />
            
            {/* Modulo 2: Pattern Lifting State Up con catalogo e carrello */}
            <Route path="/carrello" element={<LezioneCarrello />} />
            
            {/* Modulo 3: Esercitazione Task Manager (operazioni immutabili su array) */}
            <Route path="/task-manager" element={<LezioneTaskManager />} />
            
            {/* Modulo 4: Navigazione tra pagine con passaggio dati via link (query params) */}
            <Route path="/navigazione" element={<Pagina1 infoApp="Modulo Navigazione React" />} />
            <Route path="/pagina2" element={<Pagina2 infoApp="Modulo Navigazione React" />} />

            {/* Rotta di fallback 404 per qualsiasi URL non riconosciuto */}
            <Route
              path="*"
              element={
                <div style={{ textAlign: 'center', padding: '3rem' }}>
                  <h2>404 - Pagina non trovata</h2>
                  <NavLink to="/fondamenti" className="btn btn-primary">
                    Torna all'inizio
                  </NavLink>
                </div>
              }
            />
          </Routes>
        </main>

        {/* Footer della lezione */}
        <footer className="app-footer">
          <p>
            🎓 <strong>Materiale Didattico:</strong> React 19 + TypeScript + Vite | Esempi pronti per la classe
          </p>
        </footer>
      </div>
    </BrowserRouter>
  )
}
