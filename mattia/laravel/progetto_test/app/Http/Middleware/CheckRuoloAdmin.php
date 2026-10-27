<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRuoloAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verifica autenticazione
        if (!Auth::check()) {
            return redirect()->route('login')
            ->with('errore', 'Accesso riservato. Effettuare prima il login.');
        }

        // 2. Controllo del ruolo utente
        $utente = Auth::user();
        if ($utente->ruolo !== 'admin') {
            abort(403, 'Accesso Negato: permessi insufficienti.');
        }

        // 3. Proseguimento verso il Controller
        return $next($request);
    }
}