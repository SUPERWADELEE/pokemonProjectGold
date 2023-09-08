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

    // In PokemonPolicy.php
    public function show(User $user, Pokemon $pokemon)
    {
        return $user->isHost($pokemon);
    }



    public function create(User $user)
    {
        // 只有超級管理員和一般使用者可以建立
        // 此處的$user可以由依賴注入得到,因為有經過驗證的路由
        //superadmin => 常數
        //Model =>常數 設定 ex:ROLE_SUPERADMIN = 'superadmin'
        //Model method  return $user->role === ROLE_SUPERADMIN  method名字可能 isSuperadmin
        //在policy ->  return $user->isSuperadmin()
        // 這邊將會檢查$user是否是超級管理員
        // return true;
        return $user->isSuperadmin();
    }





    // 更新刪除進化等級判斷,都要使用者id該寶可夢id一致才可以進行操作
    // 或是superadmin
    public function update(User $user, Pokemon $pokemon)
    {
        return $user->isHost($pokemon);
    }


    public function delete(User $user, Pokemon $pokemon)
    {
        return $user->isHost($pokemon);
    }


    public function evolution(User $user, Pokemon $pokemon)
    {
        return $user->isHost($pokemon);
    }
}
