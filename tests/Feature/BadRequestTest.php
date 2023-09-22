<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BadRequestTest extends TestCase
{
    /**
     * 測試不存的 API 路徑應該返回 404 錯誤
     *
     * @return void
     */
    public function testNonexistentApiPathReturns404()
    {
        $response = $this->get('/api/nonexistentpath');

        $response->assertStatus(404);
    }

    // 針對錯誤的http method 給予405錯誤
    /** @test */
    public function it_returns_405_for_unallowed_method()
    {
        // Arrange: set up any needed preconditions
        $url = '/api/pokemons'; // replace with your actual endpoint

        // Act: make a PATCH request to a POST-only endpoint
        $response = $this->json('DELETE', $url);

        // Assert: check that the response has a 405 status code and the correct error message
        $response->assertStatus(405);
        $response->assertExactJson([
            'error' => 'Method not allowed'
        ]);
    }
}
