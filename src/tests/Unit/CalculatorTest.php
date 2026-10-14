<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\Calculator;  // ← まだ存在しないクラス！
use InvalidArgumentException;

class CalculatorTest extends TestCase
{
    /**
     * 2つの数を足し算できることをテストする
     */
    #[Test]
    public function it_can_add_two_numbers()
    {
        // Arrange（準備）: テストに必要なオブジェクトを用意
        $calculator = new Calculator();

        // Act（実行）: テストしたい機能を実行
        $result = $calculator->add(2, 3);

        // Assert（検証）: 期待通りの結果になっているか確認
        $this->assertEquals(5, $result);
    }

    #[Test]
    public function it_can_subtract_two_numbers()
    {
        $calculator = new Calculator();
        $result = $calculator->subtract(5, 3);
        $this->assertEquals(2, $result);
    }

    #[Test]
    public function it_can_subtract_two_numbers_minus()
    {
        $calculator = new Calculator();
        $result = $calculator->subtract(3, 5);
        $this->assertEquals(-2, $result);
    }

    #[Test]
    public function it_can_multiply_two_numbers()
    {
        $calculator = new Calculator();
        $result = $calculator->multiply(2, 3);
        $this->assertEquals(6, $result);
    }

    #[Test]
    public function it_can_divide_two_numbers()
    {
        $calculator = new Calculator();
        $result = $calculator->divide(6, 3);
        $this->assertEquals(2, $result);
    }

    #[Test]
    public function it_throws_exception_when_dividing_by_zero()
    {
        // Arrange
        $calculator = new Calculator();

        // Assert - 例外が投げられることを期待
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('0で割ることはできません');

        // Act - 例外が投げられるはず
        $calculator->divide(10, 0);
    }

}