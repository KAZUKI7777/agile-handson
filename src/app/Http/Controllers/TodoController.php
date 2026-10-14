<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TodoRepository;

class TodoController extends Controller
{
    private TodoRepository $repository;

    public function __construct(TodoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Todo一覧を表示
     */
    public function index()
    {
        $todos = $this->repository->getAll();
        return view('todos.index', compact('todos'));
    }

    /**
     * Todoを追加
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $this->repository->create($request->title);

        return redirect()->route('todos.index')
            ->with('success', 'Todoを追加しました');
    }

    /**
     * Todoを完了にする
     */
    public function complete(int $id)
    {
        $this->repository->complete($id);

        return redirect()->route('todos.index')
            ->with('success', 'Todoを完了にしました');
    }

    /**
     * Todoを削除
     */
    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return redirect()->route('todos.index')
            ->with('success', 'Todoを削除しました');
    }
}