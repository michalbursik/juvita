<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Warehouse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request@
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): before');

        /** @var User $user */
        $user = $request->user();

        if ($user->role === User::ROLE_EMPLOYEE) {
            return redirect()->route('warehouses.show', [
                'warehouse' => $user->warehouse_id
            ]);
        }

        Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): after');

        return $next($request);
    }
}
