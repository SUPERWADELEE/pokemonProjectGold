<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class PokemonIndex extends TestCase
{
    // use RefreshDatabase; // 這將在每次測試後重置數據庫
    public function testAuthOfIndex()
{
    // 未認證用戶訪問
    $response = $this->get(route('pokemon.index'));
    $response->assertRedirect(route('login'));

    // 認證用戶訪問
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->get(route('pokemon.index'));
    $response->assertStatus(200);
}

}