<?php
use Illuminate\Foundation\Testing\RefreshDatabase;

 

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
class NatureStoreTest extends TestCase
{
    use RefreshDatabase; // 使用此trait可以在每次測試後重設數據庫

    public function test_a_basic_request(): void
    {
        
        $response = $this->get('/api/natures');
 
        $response->assertStatus(200);
    }
}

    
        // public function test_cannot_store_nature_with_missing_name()
        // {
        //     // 進行 POST 請求
        //     $response = $this->postJson(route('natures.store'), []);
    
        //     // 驗證回應中的驗證錯誤
        //     $response
        //         ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY) // 422
        //         ->assertJsonValidationErrors(['name']);
        // }
    
        // public function test_cannot_store_nature_with_long_name()
        // {
        //     $natureData = ['name' => str_repeat('a', 256)]; // 256 字元的字符串
    
        //     // 進行 POST 請求
        //     $response = $this->postJson(route('natures.store'), $natureData);
    
        //     // 驗證回應中的驗證錯誤
        //     $response
        //         ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY) // 422
        //         ->assertJsonValidationErrors(['name']);
        // }
    
    
