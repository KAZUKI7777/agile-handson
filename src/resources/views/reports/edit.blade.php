@extends('layouts.app')

@section('title', '日報編集 - ' . $report->title)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">日報の編集</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $report->report_date->format('Y年m月d日') }} の日報を編集します。</p>
        </div>
        <a href="{{ route('reports.show', $report) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> 詳細に戻る
        </a>
    </div>

    <form action="{{ route('reports.update', $report) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <!-- 基本情報エリア -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-6 border-b border-slate-100">
            <div>
                <label for="report_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                    日付 <span class="text-rose-500">*</span>
                </label>
                <input type="date" name="report_date" id="report_date" value="{{ old('report_date', $report->report_date->format('Y-m-d')) }}" required
                       class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <div>
                <label for="work_location" class="block text-xs font-bold text-slate-700 mb-1.5">
                    勤務形態 <span class="text-rose-500">*</span>
                </label>
                <select name="work_location" id="work_location" required
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                    <option value="リモート" {{ old('work_location', $report->work_location) === 'リモート' ? 'selected' : '' }}>リモート</option>
                    <option value="出社" {{ old('work_location', $report->work_location) === '出社' ? 'selected' : '' }}>出社</option>
                    <option value="ハイブリッド" {{ old('work_location', $report->work_location) === 'ハイブリッド' ? 'selected' : '' }}>ハイブリッド</option>
                </select>
            </div>

            <div>
                <label for="user_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                    氏名 <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="user_name" id="user_name" value="{{ old('user_name', $report->user_name) }}" required
                       class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <div>
                <label for="department" class="block text-xs font-bold text-slate-700 mb-1.5">
                    所属部署 <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="department" id="department" value="{{ old('department', $report->department) }}" required
                       class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-slate-50">
            </div>
        </div>

        <!-- タイトル -->
        <div>
            <label for="title" class="block text-xs font-bold text-slate-700 mb-1.5">
                日報タイトル / 主要業務テーマ <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="title" id="title" value="{{ old('title', $report->title) }}" required
                   class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
        </div>

        <!-- 1. 本日の業務実績 -->
        <div>
            <label for="content" class="block text-xs font-bold text-slate-700 mb-1.5">
                1. 本日の業務実績 <span class="text-rose-500">*</span>
            </label>
            <textarea name="content" id="content" rows="6" required
                      class="w-full px-3.5 py-2.5 text-sm font-mono border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">{{ old('content', $report->content) }}</textarea>
        </div>

        <!-- 2. 課題・相談事項 -->
        <div>
            <label for="issues" class="block text-xs font-bold text-slate-700 mb-1.5">
                2. 課題・相談事項 (Blockers / Issues)
            </label>
            <textarea name="issues" id="issues" rows="3"
                      class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">{{ old('issues', $report->issues) }}</textarea>
        </div>

        <!-- 3. 明日の業務予定 -->
        <div>
            <label for="next_actions" class="block text-xs font-bold text-slate-700 mb-1.5">
                3. 明日の業務予定 (Next Actions)
            </label>
            <textarea name="next_actions" id="next_actions" rows="3"
                      class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">{{ old('next_actions', $report->next_actions) }}</textarea>
        </div>

        <!-- 4. 所感・学び -->
        <div>
            <label for="insights" class="block text-xs font-bold text-slate-700 mb-1.5">
                4. 所感・学び (Insights & Learning)
            </label>
            <textarea name="insights" id="insights" rows="3"
                      class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">{{ old('insights', $report->insights) }}</textarea>
        </div>

        <!-- 送信ボタン -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('reports.show', $report) }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                キャンセル
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <i class="fa-solid fa-check mr-2"></i> 変更を保存する
            </button>
        </div>

    </form>
</div>
@endsection
