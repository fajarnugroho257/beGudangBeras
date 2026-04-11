<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use App\Models\Menu;
use View;

class hasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $menu = null): Response
    {
        // dd($menu);
        $menuAkses = Menu::with('role_menu')->where('menu_url', $menu)->whereRelation('role_menu', 'role_id', '=', Auth::user()->role_id)->first();
        if (empty($menuAkses)) {
            // return redirect()->route('dashboard');
            abort(403, "You don't have permission");
        }
        // induk
        View::share('currentInduk', $menuAkses->app_heading_id);
        // anak
        $child = $menu;
        View::share('currentChild', $child);
        return $next($request);
    }
}
