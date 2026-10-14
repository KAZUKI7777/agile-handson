@extends('layouts.app')

@section('title', 'プランニングポーカー')

@section('content')
<div class="space-y-6">

    <!-- コントロールバー -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center">
                <i class="fa-solid fa-dice text-indigo-600 mr-2"></i> プランニングポーカー (Planning Poker)
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">タスクの規模感をフィボナッチ数で見積もり、チームの合意を形成します。</p>
        </div>

        <form action="{{ route('scrum.poker') }}" method="GET" class="inline">
            <select name="sprint_id" onchange="this.form.submit()" class="text-xs font-semibold px-3 py-1.5 border border-slate-300 rounded-lg bg-slate-50 text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">全スプリントのタスク</option>
                @foreach ($sprints as $s)
                    <option value="{{ $s->id }}" {{ $selectedSprintId == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- 左側: タスク選択リスト (4 cols) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center justify-between">
                    <span><i class="fa-solid fa-hourglass-half text-amber-500 mr-1.5"></i> 未見積もりタスク</span>
                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full text-[10px]">{{ $unestimatedTasks->count() }} 件</span>
                </h3>

                <div class="space-y-2 max-h-[450px] overflow-y-auto">
                    @forelse ($unestimatedTasks as $task)
                        <button type="button" onclick="selectTask({{ json_encode($task) }})"
                                class="task-btn text-left w-full p-3 rounded-lg border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/40 transition group">
                            <div class="text-xs font-bold text-slate-900 group-hover:text-indigo-600 mb-1">
                                {{ $task->title }}
                            </div>
                            <div class="text-[10px] text-slate-400 flex items-center justify-between">
                                <span>{{ $task->assignee ?? '未割当' }}</span>
                                <span class="px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded uppercase font-semibold">{{ $task->priority }}</span>
                            </div>
                        </button>
                    @empty
                        <div class="text-xs text-slate-400 py-6 text-center">
                            未見積もりのタスクはありません！
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 見積もり済みタスクアコーディオン -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                <details class="text-xs">
                    <summary class="font-bold text-slate-700 cursor-pointer hover:text-indigo-600 flex items-center justify-between">
                        <span><i class="fa-solid fa-check text-emerald-500 mr-1.5"></i> 見積もり済みタスク</span>
                        <span class="text-[10px] text-slate-400">再見積もり可能</span>
                    </summary>
                    <div class="mt-3 space-y-2 max-h-60 overflow-y-auto">
                        @foreach ($allTasks->whereNotNull('story_points') as $task)
                            <button type="button" onclick="selectTask({{ json_encode($task) }})"
                                    class="text-left w-full p-2.5 rounded border border-slate-100 hover:border-indigo-400 hover:bg-slate-50 transition flex items-center justify-between text-xs">
                                <span class="truncate font-medium text-slate-800">{{ $task->title }}</span>
                                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 font-bold rounded text-[11px]">{{ $task->story_points }} pt</span>
                            </button>
                        @endforeach
                    </div>
                </details>
            </div>
        </div>

        <!-- 右側: 見積もりテーブル & フィボナッチカード (8 cols) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- 選択中タスクの詳細カード -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4" id="task-detail-card">
                <div id="no-task-selected" class="py-12 text-center text-slate-400">
                    <i class="fa-solid fa-hand-pointer text-3xl mb-2 opacity-50"></i>
                    <p class="text-xs">左のリストから見積もりを行うタスクを選択してください。</p>
                </div>

                <div id="task-selected-content" class="hidden space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 px-2 py-0.5 bg-indigo-50 rounded">
                                ポーカー対象タスク
                            </span>
                            <h2 class="text-lg font-bold text-slate-900 mt-1" id="detail-title">タスク名</h2>
                        </div>
                        <span id="current-points-badge" class="px-2.5 py-1 text-xs font-bold rounded bg-slate-100 text-slate-600">
                            未設定
                        </span>
                    </div>

                    <div id="detail-userstory" class="p-3 bg-slate-50 rounded-xl text-xs text-slate-700 border border-slate-200">
                        <!-- JSで挿入 -->
                    </div>

                    <div id="detail-acceptance" class="text-xs text-slate-600">
                        <div class="font-bold text-slate-700 mb-1">受入基準 (DoD):</div>
                        <div class="p-3 bg-amber-50/50 rounded-xl border border-amber-200 text-xs whitespace-pre-line" id="detail-acceptance-text"></div>
                    </div>
                </div>
            </div>

            <!-- ポーカーカードエリア -->
            <div class="bg-gradient-to-br from-slate-900 to-indigo-950 rounded-2xl p-6 md:p-8 text-white shadow-xl space-y-6">
                <div class="text-center">
                    <h3 class="text-sm font-bold text-indigo-200 uppercase tracking-widest">Story Points Selection</h3>
                    <p class="text-xs text-slate-400 mt-1">フィボナッチ数カードを選択してください</p>
                </div>

                <!-- カードグリッド -->
                <div class="grid grid-cols-4 sm:grid-cols-8 gap-3 max-w-2xl mx-auto">
                    @foreach ($fibonacci as $pt)
                        <button type="button" onclick="chooseCard('{{ $pt }}')"
                                class="poker-card flex flex-col items-center justify-center h-24 rounded-xl border-2 border-white/20 bg-white/10 hover:bg-white hover:text-indigo-900 hover:scale-105 active:scale-95 transition-all duration-200 shadow-md group">
                            <span class="text-2xl font-black group-hover:text-indigo-700">{{ $pt }}</span>
                            <span class="text-[9px] uppercase tracking-wider opacity-60">pt</span>
                        </button>
                    @endforeach
                </div>

                <!-- 決定フォーム -->
                <div class="pt-4 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <span class="text-xs text-indigo-200">選択中ポイント:</span>
                        <div id="chosen-point-display" class="w-12 h-12 rounded-xl bg-white text-indigo-950 font-black text-2xl flex items-center justify-center shadow-lg">
                            -
                        </div>
                    </div>

                    <form id="save-points-form" method="POST" action="">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="story_points" id="hidden-points-input">
                        <button type="submit" id="save-points-btn" disabled
                                class="px-6 py-2.5 bg-indigo-500 hover:bg-indigo-600 disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-bold rounded-xl shadow-md transition">
                            <i class="fa-solid fa-check mr-1.5"></i> このポイントで決定・保存
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
let activeTaskId = null;
let chosenPoints = null;

function selectTask(task) {
    activeTaskId = task.id;
    document.getElementById('no-task-selected').classList.add('hidden');
    document.getElementById('task-selected-content').classList.remove('hidden');

    document.getElementById('detail-title').innerText = task.title;

    const pointsBadge = document.getElementById('current-points-badge');
    if (task.story_points !== null) {
        pointsBadge.innerText = '現在: ' + task.story_points + ' pt';
        pointsBadge.className = 'px-2.5 py-1 text-xs font-bold rounded bg-indigo-100 text-indigo-800';
    } else {
        pointsBadge.innerText = '未設定';
        pointsBadge.className = 'px-2.5 py-1 text-xs font-bold rounded bg-amber-100 text-amber-800';
    }

    // ユーザーストーリー
    const usEl = document.getElementById('detail-userstory');
    if (task.as_a || task.i_want || task.so_that) {
        usEl.innerHTML = `<b>As a</b>: ${task.as_a || '-'} <br><b>I want</b>: ${task.i_want || '-'} <br><b>So that</b>: ${task.so_that || '-'}`;
        usEl.classList.remove('hidden');
    } else {
        usEl.classList.add('hidden');
    }

    // 受入基準
    const accEl = document.getElementById('detail-acceptance');
    if (task.acceptance_criteria) {
        document.getElementById('detail-acceptance-text').innerText = task.acceptance_criteria;
        accEl.classList.remove('hidden');
    } else {
        accEl.classList.add('hidden');
    }

    // フォームURLの更新
    document.getElementById('save-points-form').action = `/tasks/${task.id}/points`;
    checkCanSave();
}

function chooseCard(val) {
    if (val === '?') {
        chosenPoints = null;
        document.getElementById('chosen-point-display').innerText = '?';
        document.getElementById('hidden-points-input').value = '';
    } else {
        chosenPoints = parseInt(val);
        document.getElementById('chosen-point-display').innerText = val;
        document.getElementById('hidden-points-input').value = val;
    }

    // カードのスタイル切り替え
    document.querySelectorAll('.poker-card').forEach(card => {
        if (card.querySelector('span').innerText === String(val)) {
            card.classList.add('ring-4', 'ring-indigo-400', 'bg-white', 'text-indigo-900');
        } else {
            card.classList.remove('ring-4', 'ring-indigo-400', 'bg-white', 'text-indigo-900');
        }
    });

    checkCanSave();
}

function checkCanSave() {
    const btn = document.getElementById('save-points-btn');
    if (activeTaskId && (chosenPoints !== null || document.getElementById('chosen-point-display').innerText === '?')) {
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
}

// 最初の未見積もりタスクを自動選択
document.addEventListener('DOMContentLoaded', () => {
    const firstTaskBtn = document.querySelector('.task-btn');
    if (firstTaskBtn) {
        firstTaskBtn.click();
    }
});
</script>
@endsection
