<?php

namespace App\Repositories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;

class TodoRepository
{
    /**
     * Todoを作成する
     */
    public function create(string $title): Todo
    {
        return Todo::create([
            'title' => $title,
        ]);
    }

    /**
     * 全てのTodoを取得する
     */
    public function getAll(): Collection
    {
        return Todo::all();
    }

    /**
     * Todoを完了にする
     */
    public function complete(int $id): bool
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return false;
        }

        return $todo->update(['completed' => true]);
    }

    /**
     * 未完了のTodoを取得する
     */
    public function getPending(): Collection
    {
        return Todo::where('completed', false)->get();
    }

    /**
     * Todoを削除する
     */
    public function delete(int $id): bool
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return false;
        }

        return $todo->delete();
    }
}