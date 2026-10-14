<?php

namespace App\Http\Controllers;

use App\Models\ScrumTask;
use Illuminate\Http\Request;

class ScrumTaskController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sprint_id' => 'nullable|exists:sprints,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'as_a' => 'nullable|string|max:100',
            'i_want' => 'nullable|string|max:255',
            'so_that' => 'nullable|string|max:255',
            'acceptance_criteria' => 'nullable|string',
            'assignee' => 'nullable|string|max:100',
            'story_points' => 'nullable|integer|min:0',
            'priority' => 'required|in:high,medium,low',
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        ScrumTask::create($validated);

        return back()->with('success', 'タスクを作成しました。');
    }

    public function update(Request $request, ScrumTask $task)
    {
        $validated = $request->validate([
            'sprint_id' => 'nullable|exists:sprints,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'as_a' => 'nullable|string|max:100',
            'i_want' => 'nullable|string|max:255',
            'so_that' => 'nullable|string|max:255',
            'acceptance_criteria' => 'nullable|string',
            'assignee' => 'nullable|string|max:100',
            'story_points' => 'nullable|integer|min:0',
            'priority' => 'required|in:high,medium,low',
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $task->update($validated);

        return back()->with('success', 'タスクを更新しました。');
    }

    /**
     * ステータスのみのクイック更新（カンバン用）
     */
    public function updateStatus(Request $request, ScrumTask $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $task->update(['status' => $validated['status']]);

        return back()->with('success', "ステータスを「{$validated['status']}」に更新しました。");
    }

    /**
     * ストーリーポイントのみの更新（プランニングポーカー用）
     */
    public function updatePoints(Request $request, ScrumTask $task)
    {
        $validated = $request->validate([
            'story_points' => 'nullable|integer|min:0',
        ]);

        $task->update(['story_points' => $validated['story_points']]);

        return back()->with('success', "タスク「{$task->title}」に {$validated['story_points']} pt を設定しました。");
    }

    public function destroy(ScrumTask $task)
    {
        $task->delete();
        return back()->with('success', 'タスクを削除しました。');
    }
}
