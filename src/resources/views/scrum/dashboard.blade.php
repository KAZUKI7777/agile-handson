@extends('layouts.app')

@section('title', 'スクラムダッシュボード')

@section('content')
<div class="space-y-6">

    <!-- ヘッダー -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">スクラムダッシュボード</h1>
            <p class="text-xs text-slate-500 mt-0.5">現在進行中のスプリント状況、ベロシティ、改善アクション（Try）を俯瞰します。</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('scrum.board') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                <i class="fa-solid fa-table-columns mr-1.5"></i> カンバンボードを開く
            </a>
            <a href="{{ route('scrum.poker') }}" class="inline-flex items-center px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg shadow-sm transition">
                <i class="fa-solid fa-dice mr-1.5 text-indigo-600"></i> ポーカー
            </a>
        </div>
    </div>

    @if ($activeSprint)
        <!-- アクティブスプリント概要バナー -->
        <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 text-white rounded-2xl p-6 md:p-8 shadow-lg shadow-indigo-950/10">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div class="space-y-3 max-w-2xl">
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 rounded text-[11px] font-bold uppercase tracking-wider">
                            Active Sprint
                        </span>
                        <span class="text-xs text-indigo-200">
                            <i class="fa-regular fa-calendar mr-1"></i> {{ $activeSprint->start_date->format('Y/m/d') }} 〜 {{ $activeSprint->end_date->format('Y/m/d') }}
                        </span>
                    </div>

                    <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                        {{ $activeSprint->name }}
                    </h2>

                    @if ($activeSprint->goal)
                        <div class="p-3.5 bg-white/10 rounded-xl border border-white/10 text-xs text-indigo-100">
                            <span class="font-bold text-white block mb-0.5"><i class="fa-solid fa-bullseye mr-1 text-amber-400"></i> スプリントゴール:</span>
                            {{ $activeSprint->goal }}
                        </div>
                    @endif
                </div>

                <!-- プログレスサマリーカード -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-5 border border-white/15 min-w-[260px] text-right">
                    <div class="text-xs text-indigo-200 font-semibold mb-1">スプリント進捗率</div>
                    <div class="text-4xl font-black text-white tracking-tight mb-2">
                        {{ $activeSprint->progressPercent() }}<span class="text-2xl font-light text-indigo-200">%</span>
                    </div>

                    <!-- プログレスバー -->
                    <div class="w-full bg-black/30 rounded-full h-2.5 overflow-hidden mb-3">
                        <div class="bg-gradient-to-r from-emerald-400 to-teal-300 h-2.5 rounded-full transition-all duration-500" style="width: {{ $activeSprint->progressPercent() }}%"></div>
                    </div>

                    <div class="flex justify-between items-center text-xs text-indigo-200">
                        <span>完了: <b class="text-white">{{ $activeSprint->completedPoints() }} pt</b></span>
                        <span>目標: <b class="text-white">{{ $activeSprint->totalPoints() }} pt</b></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- メトリクスグリッド -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="text-xs font-medium text-slate-500 mb-1">総タスク数</div>
                <div class="text-2xl font-black text-slate-900">{{ $activeSprint->tasks->count() }} <span class="text-xs font-normal text-slate-500">件</span></div>
                <div class="text-[11px] text-slate-400 mt-1">To Do: {{ $activeSprint->tasks->where('status', 'todo')->count() }} 件</div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="text-xs font-medium text-slate-500 mb-1">進行中 (In Progress)</div>
                <div class="text-2xl font-black text-amber-600">{{ $activeSprint->tasks->where('status', 'in_progress')->count() }} <span class="text-xs font-normal text-slate-500">件</span></div>
                <div class="text-[11px] text-slate-400 mt-1">Review中: {{ $activeSprint->tasks->where('status', 'review')->count() }} 件</div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="text-xs font-medium text-slate-500 mb-1">完了 (Done)</div>
                <div class="text-2xl font-black text-emerald-600">{{ $activeSprint->tasks->where('status', 'done')->count() }} <span class="text-xs font-normal text-slate-500">件</span></div>
                <div class="text-[11px] text-slate-400 mt-1">獲得ポイント: {{ $activeSprint->completedPoints() }} pt</div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="text-xs font-medium text-slate-500 mb-1">未見積もりタスク</div>
                <div class="text-2xl font-black text-indigo-600">{{ $activeSprint->tasks->whereNull('story_points')->count() }} <span class="text-xs font-normal text-slate-500">件</span></div>
                <a href="{{ route('scrum.poker', ['sprint_id' => $activeSprint->id]) }}" class="text-[11px] text-indigo-600 hover:underline font-bold mt-1 inline-block">
                    ポーカーで見積もる &rarr;
                </a>
            </div>
        </div>

    @else
        <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center shadow-sm">
            <div class="text-slate-400 text-3xl mb-2"><i class="fa-solid fa-flag-checkered"></i></div>
            <h2 class="text-base font-bold text-slate-800">アクティブなスプリントがありません</h2>
            <p class="text-xs text-slate-500 mb-4">新しいスプリントを作成してスクラム開発を開始しましょう。</p>
            <a href="{{ route('sprints.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold shadow-sm">
                スプリントを作成する
            </a>
        </div>
    @endif

    <!-- 2カラムコンテンツ: 直近タスク & KPTアクション -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- 直近タスク -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 flex items-center">
                    <i class="fa-solid fa-list-check text-indigo-600 mr-2"></i> スプリントタスク一覧
                </h3>
                <a href="{{ route('scrum.board') }}" class="text-xs text-indigo-600 hover:underline font-medium">
                    カンバンで見る &rarr;
                </a>
            </div>

            <div class="space-y-2.5">
                @forelse ($recentTasks as $task)
                    <div class="p-3 rounded-lg border border-slate-100 hover:border-slate-200 bg-slate-50/50 flex items-center justify-between transition">
                        <div class="flex items-center space-x-3">
                            <span class="w-6 h-6 rounded flex items-center justify-center text-xs font-bold {{ $task->status === 'done' ? 'bg-emerald-100 text-emerald-700' : ($task->status === 'in_progress' ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-700') }}">
                                {{ $task->story_points ?? '?' }}
                            </span>
                            <div>
                                <div class="text-xs font-bold text-slate-900">{{ $task->title }}</div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $task->assignee ?? '未割当' }} &bull; 優先度: {{ strtoupper($task->priority) }}
                                </div>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded {{ $task->status === 'done' ? 'bg-emerald-50 text-emerald-600' : ($task->status === 'in_progress' ? 'bg-amber-50 text-amber-600' : ($task->status === 'review' ? 'bg-sky-50 text-sky-600' : 'bg-slate-100 text-slate-600')) }}">
                            {{ strtoupper($task->status) }}
                        </span>
                    </div>
                @empty
                    <div class="text-xs text-slate-400 py-4 text-center">タスクがまだありません。</div>
                @endforelse
            </div>
        </div>

        <!-- 継続改善アクション (Try) -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 flex items-center">
                    <i class="fa-solid fa-arrows-spin text-amber-500 mr-2"></i> ふりかえり継続アクション (Try)
                </h3>
                <a href="{{ route('scrum.kpt') }}" class="text-xs text-indigo-600 hover:underline font-medium">
                    KPTボード &rarr;
                </a>
            </div>

            <div class="space-y-2.5">
                @forelse ($recentTrys as $try)
                    <div class="p-3 rounded-lg border border-amber-200/60 bg-amber-50/40 flex items-start justify-between transition">
                        <div class="flex items-start space-x-2.5">
                            <form action="{{ route('kpt.toggle', $try) }}" method="POST" class="mt-0.5">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" onchange="this.form.submit()" {{ $try->is_actioned ? 'checked' : '' }} class="rounded border-slate-300 text-amber-600 focus:ring-amber-500 cursor-pointer">
                            </form>
                            <div>
                                <p class="text-xs text-slate-800 {{ $try->is_actioned ? 'line-through text-slate-400' : 'font-medium' }}">
                                    {{ $try->content }}
                                </p>
                                <span class="text-[10px] text-slate-400">
                                    発案: {{ $try->author ?? '匿名' }} &bull; &hearts; {{ $try->votes }} 票
                                </span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $try->is_actioned ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800' }}">
                            {{ $try->is_actioned ? '実行済' : '未着手' }}
                        </span>
                    </div>
                @empty
                    <div class="text-xs text-slate-400 py-4 text-center">Tryアイテムはまだ登録されていません。</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
