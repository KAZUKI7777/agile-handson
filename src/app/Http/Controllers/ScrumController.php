<?php

namespace App\Http\Controllers;

use App\Models\KptItem;
use App\Models\ScrumTask;
use App\Models\Sprint;
use Illuminate\Http\Request;

class ScrumController extends Controller
{
    /**
     * スクラムダッシュボード
     */
    public function dashboard()
    {
        $activeSprint = Sprint::where('status', 'active')->latest()->first()
            ?? Sprint::latest()->first();

        $sprints = Sprint::orderBy('start_date', 'desc')->get();

        $recentTasks = $activeSprint
            ? $activeSprint->tasks()->orderBy('updated_at', 'desc')->take(5)->get()
            : ScrumTask::orderBy('updated_at', 'desc')->take(5)->get();

        $recentTrys = $activeSprint
            ? $activeSprint->kptItems()->where('type', 'try')->latest()->take(4)->get()
            : KptItem::where('type', 'try')->latest()->take(4)->get();

        return view('scrum.dashboard', compact('activeSprint', 'sprints', 'recentTasks', 'recentTrys'));
    }

    /**
     * カンバンボード画面
     */
    public function board(Request $request)
    {
        $sprints = Sprint::orderBy('start_date', 'desc')->get();
        $selectedSprintId = $request->get('sprint_id');

        if ($selectedSprintId) {
            $sprint = Sprint::find($selectedSprintId);
        } else {
            $sprint = Sprint::where('status', 'active')->latest()->first() ?? Sprint::latest()->first();
        }

        $query = ScrumTask::query();
        if ($sprint) {
            $query->where('sprint_id', $sprint->id);
        }

        $tasks = $query->orderBy('priority', 'asc')->get();

        $tasksByStatus = [
            'todo' => $tasks->where('status', 'todo'),
            'in_progress' => $tasks->where('status', 'in_progress'),
            'review' => $tasks->where('status', 'review'),
            'done' => $tasks->where('status', 'done'),
        ];

        return view('scrum.board', compact('sprints', 'sprint', 'tasksByStatus'));
    }

    /**
     * プランニングポーカー画面
     */
    public function poker(Request $request)
    {
        $sprints = Sprint::orderBy('start_date', 'desc')->get();
        $selectedSprintId = $request->get('sprint_id');

        $query = ScrumTask::query();
        if ($selectedSprintId) {
            $query->where('sprint_id', $selectedSprintId);
        }

        $unestimatedTasks = (clone $query)->whereNull('story_points')->get();
        $allTasks = $query->get();

        $fibonacci = [1, 2, 3, 5, 8, 13, 21, '?'];

        return view('scrum.poker', compact('sprints', 'selectedSprintId', 'unestimatedTasks', 'allTasks', 'fibonacci'));
    }

    /**
     * ふりかえり（KPT）画面
     */
    public function kpt(Request $request)
    {
        $sprints = Sprint::orderBy('start_date', 'desc')->get();
        $selectedSprintId = $request->get('sprint_id');

        if ($selectedSprintId) {
            $sprint = Sprint::find($selectedSprintId);
        } else {
            $sprint = Sprint::where('status', 'active')->latest()->first() ?? Sprint::latest()->first();
        }

        $query = KptItem::query();
        if ($sprint) {
            $query->where('sprint_id', $sprint->id);
        }

        $items = $query->orderBy('votes', 'desc')->orderBy('created_at', 'desc')->get();

        $keeps = $items->where('type', 'keep');
        $problems = $items->where('type', 'problem');
        $trys = $items->where('type', 'try');

        return view('scrum.kpt', compact('sprints', 'sprint', 'keeps', 'problems', 'trys'));
    }
}
