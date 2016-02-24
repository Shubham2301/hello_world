<?php

namespace myocuhub\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use myocuhub\User;

class ImportPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function import(User $user)
    {
        return $user->hasRole('import-user');
    }
}
