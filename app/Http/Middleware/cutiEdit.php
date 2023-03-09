<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class cutiEdit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $cuti = $request->route('cuti');
        if($cuti->cutiRequest->status_hod != config('cuti.status.pending') || $cuti->cutiRequest->status_hodp != config('cuti.status.pending')){
            return redirect()->route('cuti.index')->with('swal-error', 'Cuti cannot be edited It has been approved or rejected. Please contact the administrator.');
        }
        return $next($request);
    }
}
