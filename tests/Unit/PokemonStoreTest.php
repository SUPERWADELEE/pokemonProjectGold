<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use PHPUnit\Framework\TestCase ;

class PokemonStoreTest extends TestCase
{
    // use RefreshDatabase; // 這將在每次測試後重置數據庫

    
    public function testUserWithPermissionCanStorePokemon()
    {
        $user = User::factory()->create();
        // 假設你有一個方法來賦予用戶創建 Pokemon 的權限
        $user->givePermissionToCreatePokemon();

        // 模擬登入
        $this->actingAs($user);

        // 假設你已經設置了一個適當的請求數據
        $requestData = [
            'name' => 'Pikachu',
            // ... 其他必要的數據 ...
        ];

        $response = $this->post(route('pokemon.store'), $requestData);

        $response->assertStatus(200); // 或其他預期的成功狀態碼
    }

    public function testUserWithoutPermissionCannotStorePokemon()
    {
        $user = User::factory()->create();

        // 模擬登入
        $this->actingAs($user);

        $requestData = [
            'name' => 'Pikachu',
            // ... 其他必要的數據 ...
        ];

        $response = $this->post(route('pokemon.store'), $requestData);

        $response->assertStatus(403); // Forbidden
    }
}
