<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Verifica se o usuário NÃO tem nenhum team (nem como membro nem como proprietário)
        if (! $user->teams()->exists() && ! $user->ownedTeams()->exists()) {
            return redirect()->route('onboarding.team.create');
        }

        return $next($request);
    }
}
