<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Heading;
use App\Models\Role_menu;
use View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // View::share('rs_heading', $this->getMenuByRole());
        //compose all the views....
        view()->composer('*', function ($view)
        {
            // dd(Auth::user()->role_id);
            if (Auth::check()) {
                View::share('rs_heading', $this->getMenuByRole());
            }
        });
    }

    public function getMenuByRole() {
        //
        // $heading = Heading::whereRelation('menu', 'menu_id', '=', 'M0004')->get();
        // $heading = Heading::with('menu', 'menu.role_menu')->whereRelation('menu.role_menu', 'role_id', '=', 'R0001')->get();
        // $heading = Role_menu::with('menu')->where('role_id', '=', Auth::user()->role_id)->get();
        $heading = Heading::with('menu', 'menu.role_menu')->whereRelation('menu.role_menu', 'role_id', '=', Auth::user()->role_id)->get();
        foreach ($heading as $key => $value) {
            // echo $value->app_heading_id;
            $data_menu = Role_menu::with('menu')
            ->where('role_id', Auth::user()->role_id)
            ->whereRelation('menu.role_menu', 'app_heading_id', $value->app_heading_id)
            ->get();
            if (!empty($data_menu)) {
                $heading[$key]['data_menu'] = $data_menu;
            }
        }
        // dd($heading);
        return $heading;
    }
}
