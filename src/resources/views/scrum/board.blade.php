@extends('layouts.app')

@section('title', 'スクラム カンバンボード')

@section('content')
<div class="space-y-6">

    <!-- コントロールバー -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center">
                <i class="fa-solid fa-table-columns text-indigo-600 mr-2"></i> カンバンボード
            </h1>

            <!-- スプリントセレクター -->
            <form action="{{ route('scrum.board') }}" method="GET" class="inline">
                <select name="sprint_id" onchange="this.form.submit()" class="text-xs font-semibold px-3 py-1.5 border border-slate-300 rounded-lg bg-slate-50 text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach ($sprints as $s)
                        <option value="{{ $s->id }}" {{ $sprint && $sprint->id === $s->id ? 'selected' : '' }}>
                            {{ $s->name }} ({{ $s->status }})
                        </option>
                    @endforeach
                </select>
            </form>

            @if ($sprint)
                <span class="text-xs text-slate-500 hidden sm:inline">
                    期間: {{ $sprint->start_date->format('m/d') }} 〜 {{ $sprint->end_date->format('m/d') }}
                    &bull; 進捗: <b class="text-indigo-600">{{ $sprint->progressPercent() }}%</b>
                </span>
            @endif
        </div>

        <div>
            <button onclick="document.getElementById('task-modal').classList.remove('hidden')" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                <i class="fa-solid fa-plus mr-1.5"></i> 新規タスク追加
            </button>
        </div>
    </div>

    <!-- カンバン4列レイアウト -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">

        @php
            $columns = [
                'todo' => ['name' => 'To Do (未着手)', 'icon' => 'fa-circle-dot', 'badge' => 'bg-slate-200 text-slate-700', 'border' => 'border-slate-300'],
                'in_progress' => ['name' => 'In Progress (進行中)', 'icon' => 'fa-spinner', 'badge' => 'bg-amber-100 text-amber-800', 'border' => 'border-amber-400'],
                'review' => ['name' => 'Review (レビュー中)', 'icon' => 'fa-magnifying-glass', 'badge' => 'bg-sky-100 text-sky-800', 'border' => 'border-sky-400'],
                'done' => ['name' => 'Done (完了)', 'icon' => 'fa-circle-check', 'badge' => 'bg-emerald-100 text-emerald-800', 'border' => 'border-emerald-500'],
            ];
            $statusFlow = ['todo', 'in_progress', 'review', 'done'];
        @endphp

        @foreach ($columns as $statusKey => $col)
            <div class="bg-slate-200/60 rounded-xl p-3 flex flex-col min-h-[500px] border {{ $col['border'] }}">
                <!-- カラムヘッダー -->
                <div class="flex items-center justify-between mb-3 px-1">
                    <span class="text-xs font-bold text-slate-700 flex items-center">
                        <i class="fa-solid {{ $col['icon'] }} mr-1.5 text-xs opacity-70"></i>
                        {{ $col['name'] }}
                    </span>
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $col['badge'] }}">
                        {{ $tasksByStatus[$statusKey]->count() }} 件
                        ({{ $tasksByStatus[$statusKey]->sum('story_points') }} pt)
                    </span>
                </div>

                <!-- タスクカードリスト -->
                <div class="space-y-3 flex-1">
                    @forelse ($tasksByStatus[$statusKey] as $task)
                        <div class="bg-white rounded-lg p-3.5 shadow-sm border border-slate-200/90 hover:shadow-md transition space-y-2.5">
                            <!-- カード上部: 優先度 & ポイント -->
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="px-2 py-0.5 rounded font-bold uppercase tracking-wider {{ $task->priority === 'high' ? 'bg-rose-50 text-rose-600 border border-rose-200' : ($task->priority === 'medium' ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-slate-50 text-slate-500 border border-slate-200') }}">
                                    {{ $task->priority }}
                                </span>
                                <span class="px-2 py-0.5 rounded font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ $task->story_points !== null ? $task->story_points . ' pt' : '未見積' }}
                                </span>
                            </div>

                            <!-- タイトル -->
                            <div class="text-xs font-bold text-slate-900 leading-snug">
                                {{ $task->title }}
                            </div>

                            <!-- ユーザーストーリー要約 -->
                            @if ($task->as_a || $task->i_want)
                                <div class="p-2 bg-slate-50 rounded text-[11px] text-slate-600 border border-slate-100 leading-tight">
                                    <span class="font-semibold text-slate-700">As a:</span> {{ $task->as_a }}<br>
                                    <span class="font-semibold text-slate-700">Want:</span> {{ $task->i_want }}
                                </div>
                            @endif

                            <!-- 受入基準（アコーディオン） -->
                            @if ($task->acceptance_criteria)
                                <details class="text-[11px] text-slate-500 cursor-pointer">
                                    <summary class="font-semibold hover:text-indigo-600">受入基準 (DoD)</summary>
                                    <div class="mt-1 p-2 bg-slate-50 rounded text-[10px] whitespace-pre-line border border-slate-200">
                                        {{ $task->acceptance_criteria }}
                                    </div>
                                </details>
                            @endif

                            <!-- 担当者 & 操作 -->
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                                <div>
                                    <i class="fa-solid fa-user text-slate-400 mr-1"></i> {{ $task->assignee ?? '未割当' }}
                                </div>
                                <div class="flex items-center space-x-1">
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('タスクを削除しますか？');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-rose-600 p-1">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- ステータス変更ボタンバー -->
                            <div class="flex items-center justify-between pt-1 gap-1">
                                @php
                                    $currentIndex = array_search($statusKey, $statusFlow);
                                    $prevStatus = $currentIndex > 0 ? $statusFlow[$currentIndex - 1] : null;
                                    $nextStatus = $currentIndex < 3 ? $statusFlow[$currentIndex + 1] : null;
                                @endphp

                                @if ($prevStatus)
                                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $prevStatus }}">
                                        <button type="submit" class="w-full py-1 text-[10px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded transition font-medium">
                                            &larr; 戻す
                                        </button>
                                    </form>
                                @else
                                    <div class="flex-1"></div>
                                @endif

                                @if ($nextStatus)
                                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $nextStatus }}">
                                        <button type="submit" class="w-full py-1 text-[10px] bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded transition">
                                            進める &rarr;
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400 border-2 border-dashed border-slate-300 rounded-lg">
                            カードがありません
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach

    </div>

</div>

<!-- タスク作成モーダル -->
<div id="task-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-bold text-slate-900">新規タスク追加</h3>
            <button onclick="document.getElementById('task-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="sprint_id" value="{{ $sprint ? $sprint->id : '' }}">

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">タスクタイトル <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="例: ログインAPIの実装" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ストーリーポイント</label>
                    <select name="story_points" class="w-full px-2 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="">未定</option>
                        @foreach([1, 2, 3, 5, 8, 13, 21] as $pt)
                            <option value="{{ $pt }}">{{ $pt }} pt</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">優先度</label>
                    <select name="priority" class="w-full px-2 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="high">High</option>
                        <option value="medium" selected>Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ステータス</label>
                    <select name="status" class="w-full px-2 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="review">Review</option>
                        <option value="done">Done</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">担当者</label>
                <input type="text" name="assignee" placeholder="例: 山田 太郎" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <!-- ユーザーストーリー形式 -->
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <div class="text-[11px] font-bold text-indigo-700">ユーザーストーリー（任意）</div>
                <div class="grid grid-cols-1 gap-2 text-xs">
                    <input type="text" name="as_a" placeholder="As a (〜として): 例: 一般ユーザー" class="w-full px-2.5 py-1.5 text-xs border rounded bg-white">
                    <input type="text" name="i_want" placeholder="I want (〜したい): 例: パスワードをリセットしたい" class="w-full px-2.5 py-1.5 text-xs border rounded bg-white">
                    <input type="text" name="so_that" placeholder="So that (それにより〜できる): 例: 安全にアカウントを復旧できる" class="w-full px-2.5 py-1.5 text-xs border rounded bg-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">受入基準 / 完成の定義 (DoD)</label>
                <textarea name="acceptance_criteria" rows="2" placeholder="- パスワード再設定メールが届く&#10;- トークンの有効期限が30分である" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('task-modal').classList.add('hidden')" class="px-4 py-2 text-xs bg-slate-100 rounded-lg text-slate-700">キャンセル</button>
                <button type="submit" class="px-4 py-2 text-xs bg-indigo-600 text-white font-bold rounded-lg shadow-sm">追加する</button>
            </div>
        </form>
    </div>
</div>
@endsection
