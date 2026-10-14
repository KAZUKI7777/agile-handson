@extends('layouts.app')

@section('title', $report->title . ' - 業務日報')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- ナビゲーションバー -->
    <div class="flex items-center justify-between">
        <a href="{{ route('reports.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> 一覧に戻る
        </a>
        <div class="flex items-center space-x-2">
            <button onclick="copyAsMarkdown()" id="copy-btn" class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition shadow-sm">
                <i class="fa-regular fa-copy mr-1.5"></i> Markdown形式でコピー
            </button>
            <a href="{{ route('reports.edit', $report) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition">
                <i class="fa-solid fa-pen mr-1.5"></i> 編集
            </a>
            <form action="{{ route('reports.destroy', $report) }}" method="POST" onsubmit="return confirm('この日報を削除しますか？');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg transition">
                    <i class="fa-solid fa-trash-can mr-1.5"></i> 削除
                </button>
            </form>
        </div>
    </div>

    <!-- 日報メインカード -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- ヘッダーエリア -->
        <div class="p-6 md:p-8 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white">
            <div class="flex flex-wrap items-center gap-2 mb-3 text-xs">
                <span class="inline-flex items-center px-2.5 py-1 rounded bg-indigo-500/30 text-indigo-200 font-semibold border border-indigo-400/30">
                    <i class="fa-regular fa-calendar mr-1.5"></i> {{ $report->report_date->format('Y年m月d日') }} ({{ ['日','月','火','水','木','金','土'][$report->report_date->dayOfWeek] }})
                </span>
                <span class="inline-flex items-center px-2.5 py-1 rounded bg-white/10 text-slate-200 font-medium">
                    <i class="fa-solid fa-user mr-1.5 text-slate-300"></i> {{ $report->user_name }}
                </span>
                <span class="inline-flex items-center px-2.5 py-1 rounded bg-white/10 text-slate-200 font-medium">
                    <i class="fa-solid {{ $report->work_location === 'リモート' ? 'fa-house-laptop' : 'fa-building' }} mr-1.5"></i> {{ $report->work_location }}
                </span>
            </div>

            <h1 class="text-2xl md:text-3xl font-bold tracking-tight mb-2">
                {{ $report->title }}
            </h1>

            <div class="text-xs text-slate-400 flex items-center space-x-2">
                <span><i class="fa-regular fa-building mr-1"></i> {{ $report->department }}</span>
            </div>
        </div>

        <!-- 本文エリア -->
        <div class="p-6 md:p-8 space-y-8 divide-y divide-slate-100">

            <!-- 1. 本日の業務実績 -->
            <div class="pt-2 first:pt-0">
                <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-700 mb-3 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-indigo-600 mr-2"></span> 1. 本日の業務実績
                </h3>
                <div class="bg-slate-50 rounded-xl p-4 md:p-5 border border-slate-200/80 text-sm text-slate-800 leading-relaxed whitespace-pre-line font-mono">
{{ $report->content }}
                </div>
            </div>

            <!-- 2. 課題・相談事項 -->
            <div class="pt-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-amber-700 mb-3 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span> 2. 課題・相談事項 (Blockers / Issues)
                </h3>
                <div class="bg-amber-50/50 rounded-xl p-4 md:p-5 border border-amber-200/70 text-sm text-slate-800 leading-relaxed whitespace-pre-line">
@if($report->issues)
{{ $report->issues }}
@else
<span class="text-slate-400 italic">特になし</span>
@endif
                </div>
            </div>

            <!-- 3. 明日の業務予定 -->
            <div class="pt-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-sky-700 mb-3 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-sky-500 mr-2"></span> 3. 明日の業務予定 (Next Actions)
                </h3>
                <div class="bg-sky-50/40 rounded-xl p-4 md:p-5 border border-sky-200/70 text-sm text-slate-800 leading-relaxed whitespace-pre-line">
@if($report->next_actions)
{{ $report->next_actions }}
@else
<span class="text-slate-400 italic">未定</span>
@endif
                </div>
            </div>

            <!-- 4. 所感・学び -->
            <div class="pt-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-700 mb-3 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> 4. 所感・学び (Insights & Learning)
                </h3>
                <div class="bg-emerald-50/40 rounded-xl p-4 md:p-5 border border-emerald-200/70 text-sm text-slate-800 leading-relaxed whitespace-pre-line">
@if($report->insights)
{{ $report->insights }}
@else
<span class="text-slate-400 italic">記載なし</span>
@endif
                </div>
            </div>

        </div>

        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 text-xs text-slate-400 flex justify-between items-center">
            <span>作成日時: {{ $report->created_at->format('Y/m/d H:i') }}</span>
            <span>最終更新: {{ $report->updated_at->format('Y/m/d H:i') }}</span>
        </div>
    </div>
</div>

<!-- Markdownコピー用の不可視テキストエリア -->
<textarea id="markdown-source" class="sr-only">
# 業務日報

- **日付**: {{ $report->report_date->format('Y年m月d日') }}（{{ ['日','月','火','水','木','金','土'][$report->report_date->dayOfWeek] }}）
- **所属**: {{ $report->department }}
- **氏名**: {{ $report->user_name }}
- **本日の勤務形態**: {{ $report->work_location }}

---

## 1. 本日の業務実績
### {{ $report->title }}
{{ $report->content }}

---

## 2. 課題・相談事項 (Blockers / Issues)
{{ $report->issues ?: '特になし' }}

---

## 3. 明日の業務予定 (Next Actions)
{{ $report->next_actions ?: '未定' }}

---

## 4. 所感・学び (Insights & Learning)
{{ $report->insights ?: '特になし' }}
</textarea>

@endsection

@section('scripts')
<script>
function copyAsMarkdown() {
    const text = document.getElementById('markdown-source').value.trim();
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById('copy-btn');
        const origText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check text-emerald-600 mr-1.5"></i> コピー完了!';
        btn.classList.add('bg-emerald-50', 'text-emerald-700');
        setTimeout(() => {
            btn.innerHTML = origText;
            btn.classList.remove('bg-emerald-50', 'text-emerald-700');
        }, 2000);
    }).catch(err => {
        alert('クリップボードへのコピーに失敗しました。');
    });
}
</script>
@endsection
