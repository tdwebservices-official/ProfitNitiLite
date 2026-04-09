<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {

            if( isset(auth()->user()->id) ){
                $view->with('authUserData', Role::Join("role_has_permissions", "role_has_permissions.role_id", "=", "roles.id")
                    ->join("permissions","permissions.id","=","role_has_permissions.permission_id")
                    ->join("model_has_roles","model_has_roles.role_id","=","roles.id")
                    ->where( "model_has_roles.model_id", "=", auth()->user()->id )
                    ->pluck("permissions.name")
                    ->toArray());
            }else{
                $view->with('authUserData', Role::Join("role_has_permissions", "role_has_permissions.role_id", "=", "roles.id")
                    ->join("permissions","permissions.id","=","role_has_permissions.permission_id")
                    ->join("model_has_roles","model_has_roles.role_id","=","roles.id")                    
                    ->pluck("permissions.name")
                    ->toArray());
            }
        });       
    }
}