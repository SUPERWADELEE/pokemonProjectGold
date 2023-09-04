<?php

namespace App\Policies;

use App\Models\Pokemon;
use App\Models\User;

class PokemonPolicy
{
    protected $policies = [
        Pokemon::class => PokemonPolicy::class,
    ];

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function index(User $user)
    {
        // 所有角色都能查看
        return in_array($user->role, ['superadmin', 'user', 'guest']);
    }

    public function view(User $user, Pokemon $pokemon)
    {
        return $user->id === $pokemon->user_id || $user->role === 'superadmin';
    }


    public function create(User $user)
    {
        // 只有超級管理員和一般使用者可以建立
        // 此處的$user可以由依賴注入得到,因為有經過驗證的路由
        // dd($user->role);
        return in_array($user->role, ['superadmin', 'user']);
    }



    // 更新刪除進化等級判斷,都要使用者id該寶可夢id一致才可以進行操作
    // 或是superadmin
    public function update(User $user, Pokemon $pokemon)
    {
        return $user->id === $pokemon->user_id || $user->role === 'superadmin';
    }


    public function delete(User $user, Pokemon $pokemon)
    {
        return $user->id === $pokemon->user_id || $user->role === 'superadmin';
    }


    public function evolution(User $user, Pokemon $pokemon)
    {
        return $user->id === $pokemon->user_id || $user->role === 'superadmin';
    }


    
}
