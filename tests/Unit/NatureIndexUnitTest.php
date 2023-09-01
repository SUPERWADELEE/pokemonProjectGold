<?php

use Tests\TestCase;
use App\Http\Controllers\NatureController;
use App\Models\Nature;


class NatureIndexUnitTest extends TestCase
{
    public function test_store_method_of_natures()
    {
        // 模拟 Nature 数据，使用与实际返回数据相同的关联数组格式
        // $mockedNature = Nature::factory()->make()->toArray();
        $mockedNature = [
            'id' => 1,
            'name' => 'Bold',
            'increased_stat' => 'Defense',
            'decreased_stat' => 'Attack',
            // ... 其他欄位
        ];
        
        $mockedNatures = [$mockedNature];

        // 创建一个 Mockery 的期望
        // 這裡定義了使用靜態方法去調用Nature的method
        $mockNature = Mockery::mock('alias:' . Nature::class)->makePartial();;

        $mockNature->shouldReceive('all')
            ->once()
            ->andReturn(collect($mockedNatures)); // 注意这里使用了 collect() 函数
        $this->app->instance(Nature::class, $mockNature);

        // 实例化控制器


        // 调用控制器的 index 方法
        $result = Nature::all();
        

        $this->assertEquals($mockedNatures, $result->toArray()); // 将集合对象转换为数组再比较
    }

    public function tearDown(): void
    {
        Mockery::close(); // 关闭 Mockery 对象，释放资源
        parent::tearDown(); // 调用父类的 tearDown 方法
    }
}
