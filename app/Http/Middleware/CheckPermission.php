<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class CheckPermission {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $page, $permission = "open") {

        if (!\Permissions::check($page, $permission)) {
            if ($request->ajax()) {
                App()->abort(403, _lang('app.access_denied'));
            } else {
                return redirect()->route('admin.error');
            }
        }
        return $next($request);
    }

}
