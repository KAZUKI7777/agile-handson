@extends('layouts.app')

@section('title', '日報一覧')

@section('content')
<div class="space-y-6">

    <!-- タイトル部 -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">日報一覧</h1>
            <p class="text-sm text-slate-500 mt-1">日付やキーワードで絞り込んで日報を検索・確認できます。</p>
        </div>
        <div>
            <a href="{{ route('reports.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-lg shadow-sm transition">
                <i class="fa-solid fa-pen-to-square mr-2"></i> 本日の日報を作成
            </a>
        </div>
    </div>

    <!-- 検索フィルターカード -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <form action="{{ route('reports.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                <!-- 特定日指定 -->
                <div class="md:col-span-3">
                    <label for="date" class="block text-xs font-semibold text-slate-600 mb-1">
                        <i class="fa-regular fa-calendar-check text-indigo-500 mr-1"></i> 指定日（完全一致）
                    </label>
                    <input type="date" name="date" id="date" value="{{ request('date') }}"
                           class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>

                <!-- 期間指定 (From - To) -->
                <div class="md:col-span-5">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        <i class="fa-regular fa-calendar-days text-indigo-500 mr-1"></i> 期間指定 (開始日 〜 終了日)
                    </label>
                    <div class="flex items-center space-x-2">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="開始日"
                               class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <span class="text-slate-400 text-xs">〜</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="終了日"
                               class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                </div>

                <!-- キーワード検索 -->
                <div class="md:col-span-4">
                    <label for="keyword" class="block text-xs font-semibold text-slate-600 mb-1">
                        <i class="fa-solid fa-magnifying-glass text-indigo-500 mr-1"></i> キーワード検索
                    </label>
                    <div class="relative">
                        <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}" placeholder="氏名、タイトル、業務内容など"
                               class="w-full pl-9 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <span class="absolute left-3 top-2.5 text-slate-400 text-xs">
                            <i class="fa-solid fa-search"></i>
                        </span>
                    </div>
                </div>

            </div>

            <!-- クイック日付ボタン & 検索ボタン -->
            <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-1.5 text-xs">
                    <span class="text-slate-400 mr-1 font-medium">クイック日付:</span>
                    <a href="{{ route('reports.index', ['date' => now()->format('Y-m-d')]) }}"
                       class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 transition">今日</a>
                    <a href="{{ route('reports.index', ['date' => now()->subDay()->format('Y-m-d')]) }}"
                       class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 transition">昨日</a>
                    <a href="{{ route('reports.index', ['date_from' => now()->subDays(7)->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}"
                       class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 transition">過去7日間</a>
                    <a href="{{ route('reports.index', ['date_from' => now()->startOfMonth()->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}"
                       class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 transition">今月</a>
                </div>

                <div class="flex items-center space-x-2">
                    @if(request()->hasAny(['date', 'date_from', 'date_to', 'keyword']))
                        <a href="{{ route('reports.index') }}" class="px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                            <i class="fa-solid fa-rotate-left mr-1"></i> 検索解除
                        </a>
                    @endif
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        <i class="fa-solid fa-filter mr-1.5"></i> この条件で絞り込む
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 検索結果情報 -->
    <div class="flex items-center justify-between text-xs text-slate-500 px-1">
        <div>
            該当件数: <span class="font-bold text-slate-800">{{ $reports->total() }}</span> 件
            @if(request('date'))
                (指定日: <span class="font-medium text-indigo-600">{{ request('date') }}</span>)
            @endif
            @if(request('date_from') || request('date_to'))
                (期間: <span class="font-medium text-indigo-600">{{ request('date_from', '〜') }} から {{ request('date_to', '現在') }}</span>)
            @endif
            @if(request('keyword'))
                (キーワード: <span class="font-medium text-indigo-600">"{{ request('keyword') }}"</span>)
            @endif
        </div>
    </div>

    <!-- 日報リスト -->
    @if ($reports->count() > 0)
        <div class="space-y-4">
            @foreach ($reports as $report)
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    <i class="fa-regular fa-calendar mr-1.5"></i> {{ $report->report_date->format('Y年m月d日') }} ({{ ['日','月','火','水','木','金','土'][$report->report_date->dayOfWeek] }})
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">
                                    <i class="fa-solid fa-user mr-1 text-slate-400"></i> {{ $report->user_name }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $report->work_location === 'リモート' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                    <i class="fa-solid {{ $report->work_location === 'リモート' ? 'fa-house-laptop' : 'fa-building' }} mr-1"></i> {{ $report->work_location }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ $report->department }}
                                </span>
                            </div>

                            <h2 class="text-lg font-bold text-slate-900 hover:text-indigo-600 transition mb-2">
                                <a href="{{ route('reports.show', $report) }}">
                                    {{ $report->title }}
                                </a>
                            </h2>

                            <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed whitespace-pre-line mb-3">
                                {{ Str::limit($report->content, 200) }}
                            </p>

                            @if($report->issues)
                                <div class="mt-2 text-xs text-amber-800 bg-amber-50 rounded p-2 flex items-start space-x-1.5">
                                    <i class="fa-solid fa-circle-exclamation text-amber-500 mt-0.5"></i>
                                    <div><span class="font-semibold">課題/相談:</span> {{ Str::limit($report->issues, 100) }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="flex sm:flex-col items-center sm:items-end justify-end space-x-2 sm:space-x-0 sm:space-y-2 pt-2 sm:pt-0">
                            <a href="{{ route('reports.show', $report) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                                <i class="fa-regular fa-eye mr-1"></i> 詳細
                            </a>
                            <a href="{{ route('reports.edit', $report) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition">
                                <i class="fa-solid fa-pen mr-1"></i> 編集
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl border border-slate-200 p-12 text-center shadow-sm">
            <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 text-2xl">
                <i class="fa-regular fa-folder-open"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-1">日報が見つかりませんでした</h3>
            <p class="text-xs text-slate-500 mb-4">検索条件を変更するか、新しい日報を登録してください。</p>
            <div class="flex items-center justify-center space-x-3">
                @if(request()->hasAny(['date', 'date_from', 'date_to', 'keyword']))
                    <a href="{{ route('reports.index') }}" class="px-4 py-2 text-xs font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                        検索条件をリセット
                    </a>
                @endif
                <a href="{{ route('reports.create') }}" class="px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                    新規作成する
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
