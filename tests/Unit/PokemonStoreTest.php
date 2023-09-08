<?php

use App\Http\Requests\StorePokemonRequest;
use App\Models\Ability;
use App\Models\Nature;
use App\Models\Race;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
// use Dotenv\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Lcobucci\JWT\Validation\Validator;
use Tests\TestCase;

// use PHPUnit\Framework\TestCase ;

class PokemonStoreTest extends TestCase
{
    use DatabaseMigrations;
    // 權限驗證目前的邏輯是:只有superadmin可以新增, 其他不能新增
    // 權限驗證(有通過的情況)
    public function testUserWithCreatePermissionCanStorePokemon()
    {
        $mockData = $this->createMockData();

        $data = [
            "name" => "god2",
            "race_id" => $mockData['race']->id,
            "skills" => $mockData['skills']->pluck('id')->toArray(),
            "ability_id" => $mockData['ability']->id,
            "nature_id" => $mockData['nature']->id,
            "level" => $mockData['race']->evolution_level - 1,
            "user_id" => $mockData['user']->id
        ];


        // 模擬使用者登入  並發送請求
        // 定義預期返回的格式
        $response = $this->actingAs($mockData['user'], 'api')->post(route('pokemons.store'), $data);

        // 定義預期返回的格式
        $response->assertStatus(201);  // Assuming 201 means created

    }


    // 權限驗證(沒通過的情況)
    public function testUserWithoutCreatePermissionCannotStorePokemon()
    {
        $mockData = $this->createMockData($role = 'user');
        // 生成一些一定要輸入的參數
        $data = [
            "name" => "god2",
            "race_id" => $mockData['race']->id,
            "skills" => $mockData['skills']->pluck('id')->toArray(),
            "ability_id" => $mockData['ability']->id,
            "nature_id" => $mockData['nature']->id,
            "level" => $mockData['race']->evolution_level - 1,
            "user_id" => $mockData['user']->id
        ];

        // 模擬使用者登入  並發送請求
        // 定義預期返回的格式
        $response = $this->actingAs($mockData['user'], 'api')->post(route('pokemons.store'), $data);

        $response->assertStatus(403);  // Assuming 403 means forbidden
    }





    // 表單驗證（有通過的情況）
    public function testValidPokemonData()
    {

        $mockData = $this->createMockData();

        $data = [
            "name" => "god2",
            "race_id" => $mockData['race']->id,
            "skills" => $mockData['skills']->pluck('id')->toArray(),
            "ability_id" => $mockData['ability']->id,
            "nature_id" => $mockData['nature']->id,
            "level" => $mockData['race']->evolution_level - 1,
            "user_id" => $mockData['user']->id
        ];


        // 模擬使用者登入  並發送請求
        // 定義預期返回的格式
        $response = $this->actingAs($mockData['user'], 'api')->post(route('pokemons.store'), $data);

        // 定義預期返回的格式
        $response->assertStatus(201);  // Assuming 201 means created


        // $data = [
        //     // Valid pokemon data
        // ];
        // $request = new StorePokemonRequest();

        // $validator = Validator::make($data, $request->rules());

        // $this->assertTrue($validator->passes());
    }

    // 表單驗證（沒有通過的情況）
    public function testInvalidPokemonData()
    {
        $mockData = $this->createMockData();

        $data = [
            "name" => "god2",
            "race_id" => $mockData['race']->id,
            "skills" => $mockData['skills']->pluck('id')->toArray(),
            "ability_id" => $mockData['ability']->id,
            "nature_id" => $mockData['nature']->id,
            "level" => $mockData['race']->evolution_level - 1,
            "user_id" => $mockData['user']->id
        ];


        // 模擬使用者登入  並發送請求
        // 定義預期返回的格式
        $response = $this->actingAs($mockData['user'], 'api')
            ->withHeaders([
                'Accept' => 'application/json',
                // ... any other headers
            ])
            ->post(route('pokemons.store'), $data);


        // 定義預期返回的格式
        $response->assertStatus(422);  // Assuming 201 means created
        // $data = [
        //     // Invalid pokemon data
        // ];
        // $request = new StorePokemonRequest();

        // $validator = Validator::make($data, $request->rules());

        // $this->assertFalse($validator->passes());
    }


    // 數據庫建立數據（有成功
    public function testPokemonIsCreatedWithValidData()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            // Valid pokemon data
        ];

        $response = $this->post(route('pokemon.store'), $data);

        $this->assertDatabaseHas('pokemons', $data);
    }

    // 數據庫建立數據（沒有成功
    public function testPokemonIsNotCreatedWithInvalidData()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            // Invalid pokemon data
        ];

        $response = $this->post(route('pokemon.store'), $data);

        $this->assertDatabaseMissing('pokemons', $data);
    }


    private function createMockData($role = 'superadmin')
    {
        $race = Race::factory()->create();
        $user = User::factory()->create(['role' => $role]);
        $nature = Nature::factory()->create();
        $ability = Ability::factory()->create();
        $skills = Skill::factory(4)->create();

        $race->skills()->attach($skills->pluck('id'));

        return compact('race', 'user', 'nature', 'ability', 'skills');
    }

}
