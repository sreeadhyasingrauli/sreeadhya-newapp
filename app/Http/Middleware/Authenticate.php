<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    public function redirectTo (Request $request): ?string
    {
        
        return $request->expectsJson() ? response()->json(['message' => 'Unauthenticated.'], 401) : redirect()->route('login');
        
    }
}