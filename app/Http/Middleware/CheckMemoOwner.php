<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Memo;

class CheckMemoOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = auth()->id();
        $memo = Memo::findOrFail($request->id);

        if ($memo->user_id !== $currentUser) {
            return abort(404);
        }

        return $next($request);
    }
}
