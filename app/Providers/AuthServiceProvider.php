<?php

namespace myocuhub\Providers;

use myocuhub\Permission;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'myocuhub\Model' => 'myocuhub\Policies\ModelPolicy',
        'myocuhub\Patient' => 'myocuhub\Policies\PatientPolicy',
        'myocuhub\User' => 'myocuhub\Policies\UserPolicy',
        'myocuhub\Models\Practice' => 'myocuhub\Policies\PracticePolicy',
        'myocuhub\Network' => 'myocuhub\Policies\NetworkPolicy',
        'myocuhub\Models\Careconsole' => 'myocuhub\Policies\CareconsolePolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        foreach ($this->getPermissions() as $permission) {
            $gate->define($permission->name, function($user) use ($permission) {
				if($user->isSuperAdmin())
				{
					return true;
				}
                return $user->hasRole($permission->roles);
            });
        }
    }

    public function getPermissions()
    {
        return Permission::with('roles')->get();
    }
}
