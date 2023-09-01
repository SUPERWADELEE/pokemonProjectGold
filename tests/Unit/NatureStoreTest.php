<?php

namespace Tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

use PHPUnit\Framework\TestCase;

class NatureStoreTest extends TestCase
{
    
        use RefreshDatabase; // 使用此 trait 以確保每次測試後，資料庫重新建立。
    
        public function test_can_store_nature_with_valid_data()
        {
            $natureData = ['name' => 'Bold'];
    
            // 進行 POST 請求
            $response = $this->json('POST', route('natures.store'), $natureData);

    
            // 檢查是否在資料庫中
            $this->assertDatabaseHas('natures', $natureData);
    
            // 驗證回應
            $response
                ->assertStatus(Response::HTTP_CREATED) // 201
                ->assertJson(['message' => 'Nature saved successfully']);
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
    }
    
