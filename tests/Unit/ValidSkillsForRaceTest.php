<?php

namespace Tests\Unit;

use App\Models\Race; // 引入 Race 类
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;




class ValidSkillsForRaceTest extends TestCase
{
    // public function testValidSkillsForRace() {
    //     // $requestMock = $this->createMock(Request::class);

    //     $raceMock = $this->createMock(Race::class);
    //     $raceMock->method('getAttribute')->with('skills')->willReturn(collect([
    //         (object) ['id' => 1],
    //         (object) ['id' => 2],
    //         (object) ['id' => 3],
    //     ]));

    //     $result = validSkillsForRace([1, 2], $raceMock);

    //     $this->assertTrue($result);
    // }

    public function testValidSkillsForRaceIftheSkillsExist()
    {
        // 创建 Race 类的模拟对象
        $raceMock = $this->getMockBuilder(Race::class)
            ->onlyMethods(['skills']) // 指定仅模拟 `skills` 方法
            ->getMock();
    
        // 创建 Collection 类的模拟对象
        // 模拟了 BelongsToMany 关系类的一个实例，用来模拟 $race->skills 的返回值。
        $skillsRelationMock = $this->createMock(BelongsToMany::class);
        // 模拟了 Collection 类的一个实例，用来模拟 $race->skills->pluck('id') 的返回值。
        $skillsCollectionMock = $this->createMock(Collection::class);
    
        // 修改这里：使 `pluck` 返回 Collection 对象而不是数组
        $skillsCollectionMock->method('pluck')
            ->willReturn(collect([1, 2, 3]));
    
        // Laravel 在底层会调用 getResults 来获取这个关系对应的模型集合。
        $skillsRelationMock->method('getResults')
            ->willReturn($skillsCollectionMock);
    
        $raceMock->method('skills')
            ->willReturn($skillsRelationMock);
    
        // 上述過程都在模擬$race->skills->pluck('id')  

        
        // 调用待测函数，并检查返回值
        $result = validSkillsForRace([1, 2], $raceMock);
    
        // 断言结果为真
        $this->assertTrue($result);
    }


    public function testValidSkillsForRaceIfTheSkillNotExist()
    {
        // 创建 Race 类的模拟对象
        $raceMock = $this->getMockBuilder(Race::class)
            ->onlyMethods(['skills']) // 指定仅模拟 `skills` 方法
            ->getMock();
    
        // 创建 Collection 类的模拟对象
        $skillsRelationMock = $this->createMock(BelongsToMany::class);
        $skillsCollectionMock = $this->createMock(Collection::class);
    
        // 修改这里：使 `pluck` 返回 Collection 对象而不是数组
        $skillsCollectionMock->method('pluck')
            ->willReturn(collect([1, 2, 3]));
    
        
        $skillsRelationMock->method('getResults')
            ->willReturn($skillsCollectionMock);
    
        $raceMock->method('skills')
            ->willReturn($skillsRelationMock);
    
        // 调用待测函数，并检查返回值
        $result = validSkillsForRace([1, 2, 10], $raceMock);
    
        // 断言结果为真
        $this->assertFalse($result);
    }
    
}
