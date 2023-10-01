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
    // 如果我user事superadmin的情況, 新增會正確
    // 如果不是,則錯誤
    // 權限驗證(有通過的情況)
    public function testUserPermission()
    {
        // 模擬要傳入的參數

        // 返回值為一個模型實例的陣列
        $mockedData = $this->createMockData();
        // 從該實例陣列取得user實例
        $getUser = $mockedData['user'];

        // 將模型實例傳入,以便於在函式裡面取得id
        // 第二個參數為, 可以複寫屬性
        $data = $this->getMockData($mockedData,[
            'level' => 100
        ]);

        // 模擬使用者登入  並發送請求

        // 我的user欄位預設是superadmin
        $response = $this->actingAs($getUser, 'api')->post(route('pokemons.store'), $data);

        // 定義預期返回的格式
        $response->assertStatus(201);  // Assuming 201 means created

    }


    // 權限驗證(沒通過的情況)
    public function testUserWithoutPermission()
    {
          // 模擬要傳入的參數

        // 返回值為一個模型實例的陣列
        $mockedData = $this->createMockData($role = 'user');
        // 從該實例陣列取得user實例
        $getUser = $mockedData['user'];

        // 將模型實例傳入,以便於在函式裡面取得id
        // 第二個參數為, 可以複寫屬性
        $data = $this->getMockData($mockedData,[
            'level' => 100
        ]);

        // 模擬使用者登入  並發送請求

        // 我的user欄位預設是superadmin
        $response = $this->actingAs($getUser, 'api')->post(route('pokemons.store'), $data);
        $response->assertStatus(403);  // Assuming 403 means forbidden
    }

// 當有進化等級的時候, 我等級驗證規則的最大值是最小進化等級
// 當我最小進化等級為50 我輸入51出現錯誤
// 當我最小進化等級為50  我輸入50 沒有錯誤, 代表我等級驗證規則的最大值是最小進化等級
    public function testEvolution_levelJudgementError(){

         // 返回值為一個模型實例的陣列
         $mockedData = $this->createMockData($role = 'superadmin', $evolution_level = 50);
         // 從該實例陣列取得user實例
         $getUser = $mockedData['user'];
 
         // 將模型實例傳入,以便於在函式裡面取得id
         // 第二個參數為, 可以複寫屬性
         $data = $this->getMockData($mockedData,[
             'level' => 51
         ]);

        // 模擬使用者登入  並發送請求
        // 在頭部加上此自斷他才不會重定向
        $response = $this->actingAs($getUser, 'api')
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post(route('pokemons.store'), $data);

            // 定義預期返回的格式
        $response->assertStatus(422); 
    }


    public function testEvolution_levelJudgement(){

        // 返回值為一個模型實例的陣列
        $mockedData = $this->createMockData($role = 'superadmin', $evolution_level = 50);
        // 從該實例陣列取得user實例
        $getUser = $mockedData['user'];

        // 將模型實例傳入,以便於在函式裡面取得id
        // 第二個參數為, 可以複寫屬性
        $data = $this->getMockData($mockedData,[
            'level' => 50
        ]);

       // 模擬使用者登入  並發送請求
       // 在頭部加上此自斷他才不會重定向
       $response = $this->actingAs($getUser, 'api')
           ->withHeaders([
               'Accept' => 'application/json',
           ])
           ->post(route('pokemons.store'), $data);

           // 定義預期返回的格式
       $response->assertStatus(201); 
   }





//    當沒有進化等級的時候,我的進化等級驗證規則最大值要是100
// 前面測過輸入一百的時候沒問題
// 如果現在輸入101 出現錯誤則表示我的最大值事100
public function testEvolution_levelMaxValue(){

    // 返回值為一個模型實例的陣列
    $mockedData = $this->createMockData($role = 'superadmin');
    // 從該實例陣列取得user實例
    $getUser = $mockedData['user'];

    // 將模型實例傳入,以便於在函式裡面取得id
    // 第二個參數為, 可以複寫屬性
    $data = $this->getMockData($mockedData,[
        'level' => 101
    ]);

   // 模擬使用者登入  並發送請求
   // 在頭部加上此自斷他才不會重定向
   $response = $this->actingAs($getUser, 'api')
       ->withHeaders([
           'Accept' => 'application/json',
       ])
       ->post(route('pokemons.store'), $data);

       // 定義預期返回的格式
   $response->assertStatus(422); 
}



// 如果我輸入的技能不為這個種族所能學的則應該出現錯誤


public function testSkillNotBelongsToRace(){

    // 返回值為一個模型實例的陣列
    $mockedData = $this->createMockData($role = 'superadmin');
    // 從該實例陣列取得user實例
    $getUser = $mockedData['user'];

    // 將模型實例傳入,以便於在函式裡面取得id
    // 第二個參數為, 可以複寫屬性
    $data = $this->getMockData($mockedData,[
        'skills' => [9999,6666,7777,77777]
    ]);

   // 模擬使用者登入  並發送請求
   // 在頭部加上此自斷他才不會重定向
   $response = $this->actingAs($getUser, 'api')
       ->withHeaders([
           'Accept' => 'application/json',
       ])
       ->post(route('pokemons.store'), $data);

       // 定義預期返回的格式
   $response->assertStatus(422); 
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


    public function getMockData($mockData, $overrides = [])
    {

        // $mockData = $this->createMockData($role);

        // 接收數據  組裝
        $data = [
            "name" => "god2",
            "race_id" => $mockData['race']->id,
            "skills" => $mockData['skills']->pluck('id')->toArray(),
            "ability_id" => $mockData['ability']->id,
            "nature_id" => $mockData['nature']->id,
            "level" => $mockData['race']->evolution_level - 1,
            "user_id" => $mockData['user']->id
        ];

        // 用這樣的方法可以彈性的讓使用者輸入參數去修改陣列裡的數值
        return array_merge($data, $overrides);
    }



    private function createMockData($role = 'superadmin', $evolution_level = null)
    {
        // 創造數據返回id
        $race = Race::factory()->create(['evolution_level' => $evolution_level]);
        $user = User::factory()->create(['role' => $role]);
        $nature = Nature::factory()->create();
        $ability = Ability::factory()->create();
        $skills = Skill::factory(4)->create();

        $race->skills()->attach($skills->pluck('id'));

        return compact('race', 'user', 'nature', 'ability', 'skills');
    }
}
