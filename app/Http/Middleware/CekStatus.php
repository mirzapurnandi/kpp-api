<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;

class CekStatus
{
    use ApiResponser;

    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->status == 1) {
            return $next($request);
        }
        return $this->errorResponse('Akun Anda belum aktif, terimakasih');
    }
}
