<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\TodoList;

class TodoListTest extends TestCase
{
    /**
     * Todoを追加できることをテストする
     */
    #[Test]
    public function test_it_can_add_todo()
    {
        $todoList = new TodoList();
        $todoList->add('買い物に行く');
        $todos = $todoList->getAll();
        $this->assertCount(1, $todos);
        $this->assertEquals('買い物に行く', $todos[0]['title']);

    }
    /**
 * Todoを完了できることをテストする
 */
    #[Test]
    public function it_can_complete_a_todo()
    {
        // Arrange
        $todoList = new TodoList();
        $todoList->add('買い物に行く');

        // Act
        $todoList->complete(0); // インデックス0のTodoを完了にする

        // Assert
        $todos = $todoList->getAll();
        $this->assertTrue($todos[0]['completed']);
    }


    /**
     * 未完了のTodoだけを取得できることをテストする
     */
    #[Test]
    public function it_can_get_pending_todos_only()
    {
        // Arrange
        $todoList = new TodoList();
        $todoList->add('買い物に行く');
        $todoList->add('掃除をする');
        $todoList->add('勉強する');
        $todoList->complete(1); // 2つ目を完了にする

        // Act
        $pending = $todoList->getPending();

        // Assert
        $this->assertCount(2, $pending); // 未完了は2つ
        $this->assertEquals('買い物に行く', $pending[0]['title']);
        $this->assertEquals('勉強する', $pending[1]['title']);
    }
    /**
 * Todoを削除できることをテストする
 */
#[Test]
public function it_can_remove_a_todo()
{
    // Arrange
    $todoList = new TodoList();
    $todoList->add('買い物に行く');
    $todoList->add('掃除をする');

    // Act
    $todoList->remove(0);

    // Assert
    $todos = $todoList->getAll();
    $this->assertCount(1, $todos);
    $this->assertEquals('掃除をする', $todos[0]['title']);
}
}
