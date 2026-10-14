<?php

namespace App\Services;

class TodoList
{
    private array $todos = [];

    /**
     * Todoを追加する
     */
    public function add(string $title): void
    {
        $this->todos[] = [
            'title' => $title,
            'completed' => false,
        ];
    }

    /**
     * 全てのTodoを取得する
     */
    public function getAll(): array
    {
        return $this->todos;
    }

    /**
     * 未完了のTodoだけを取得する
     */
    public function getPending(): array
    {
        return array_values(array_filter(
            $this->todos,
            fn($todo) => !$todo['completed']
        ));
    }
        /**
     * Todoを完了にする
     */
    public function complete(int $index): void
    {
        if (isset($this->todos[$index])) {
            $this->todos[$index]['completed'] = true;
        }
    }
    /**
 * Todoを削除する
 */
public function remove(int $index): void
{
    if (isset($this->todos[$index])) {
        unset($this->todos[$index]);
        $this->todos = array_values($this->todos); // インデックスを振り直す
    }
}
}