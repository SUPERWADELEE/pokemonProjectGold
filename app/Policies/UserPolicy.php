<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];
    
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    public function changePermission(User $user)
    {
        return $user->role === 'superadmin';
    }
}
