<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * Gate
         */
        
        /* Gate::define('view-dashboard', function(User $user){
            return $user->role->hasPermission('view-dashborad');
        }); */
        
        // handle permissions 
        
        foreach($this->getPermissions() as $permission){
            Gate::define($permission->title,function($user) use ($permission){
                return $user->role->hasPermission($permission->title);
            });
        }

    }

    public function getPermissions()
    {
        return Permission::with('roles')->get();
    }
}
