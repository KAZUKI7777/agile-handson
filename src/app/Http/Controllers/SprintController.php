<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    public function index()
    {
        $sprints = Sprint::withCount('tasks')
            ->orderBy('start_date', 'desc')
            ->get();
        return view('sprints.index', compact('sprints'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'goal' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,completed',
        ]);

        // activeに変更する場合、既存のactiveを保持または運用可能
        Sprint::create($validated);

        return back()->with('success', 'スプリントを作成しました。');
    }

    public function update(Request $request, Sprint $sprint)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'goal' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,completed',
        ]);

        $sprint->update($validated);

        return back()->with('success', 'スプリント情報を更新しました。');
    }

    public function destroy(Sprint $sprint)
    {
        $sprint->delete();
        return back()->with('success', 'スプリントを削除しました。');
    }
}
