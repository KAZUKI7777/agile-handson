<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DailyReport::query();

        // 日付完全一致検索
        if ($request->filled('date')) {
            $query->whereDate('report_date', $request->date);
        }

        // 日付範囲検索（開始日）
        if ($request->filled('date_from')) {
            $query->whereDate('report_date', '>=', $request->date_from);
        }

        // 日付範囲検索（終了日）
        if ($request->filled('date_to')) {
            $query->whereDate('report_date', '<=', $request->date_to);
        }

        // キーワード検索（氏名、タイトル、内容）
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('user_name', 'like', "%{$keyword}%")
                  ->orWhere('content', 'like', "%{$keyword}%")
                  ->orWhere('insights', 'like', "%{$keyword}%");
            });
        }

        $reports = $query->orderBy('report_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $today = now()->format('Y-m-d');
        $defaultDepartment = 'デジタル先端技術本部 クラウドアーキテクチャ推進グループ';
        return view('reports.create', compact('today', 'defaultDepartment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_date' => 'required|date',
            'department' => 'required|string|max:255',
            'user_name' => 'required|string|max:100',
            'work_location' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'issues' => 'nullable|string',
            'next_actions' => 'nullable|string',
            'insights' => 'nullable|string',
        ]);

        $report = DailyReport::create($validated);

        return redirect()->route('reports.show', $report)
            ->with('success', '日報を登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyReport $report)
    {
        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyReport $report)
    {
        return view('reports.edit', compact('report'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyReport $report)
    {
        $validated = $request->validate([
            'report_date' => 'required|date',
            'department' => 'required|string|max:255',
            'user_name' => 'required|string|max:100',
            'work_location' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'issues' => 'nullable|string',
            'next_actions' => 'nullable|string',
            'insights' => 'nullable|string',
        ]);

        $report->update($validated);

        return redirect()->route('reports.show', $report)
            ->with('success', '日報を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyReport $report)
    {
        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', '日報を削除しました。');
    }
}
