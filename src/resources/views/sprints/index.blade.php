@extends('layouts.app')

@section('title', 'スプリント管理')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center">
                <i class="fa-solid fa-flag-checkered text-indigo-600 mr-2"></i> スプリント管理
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">スプリントの期間、ゴール、ステータス（Active / Planning / Completed）を管理します。</p>
        </div>

        <button onclick="document.getElementById('sprint-modal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
            <i class="fa-solid fa-plus mr-1.5"></i> 新規スプリント作成
        </button>
    </div>

    <!-- スプリントリスト -->
    <div class="space-y-4">
        @forelse ($sprints as $sprint)
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider {{ $sprint->status === 'active' ? 'bg-emerald-100 text-emerald-800' : ($sprint->status === 'planning' ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-600') }}">
                                {{ $sprint->status }}
                            </span>
                            <span class="text-xs text-slate-500">
                                <i class="fa-regular fa-calendar mr-1"></i> {{ $sprint->start_date->format('Y/m/d') }} 〜 {{ $sprint->end_date->format('Y/m/d') }}
                            </span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900">{{ $sprint->name }}</h2>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="{{ route('scrum.board', ['sprint_id' => $sprint->id]) }}" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-lg transition">
                            <i class="fa-solid fa-table-columns mr-1"></i> ボードへ
                        </a>
                        <form action="{{ route('sprints.destroy', $sprint) }}" method="POST" onsubmit="return confirm('スプリントを削除しますか？紐づくタスクは解除されます。');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-rose-600 p-1.5 text-xs">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>

                @if ($sprint->goal)
                    <div class="p-3 bg-slate-50 rounded-lg text-xs text-slate-700 border border-slate-100">
                        <b class="text-slate-900 mr-1"><i class="fa-solid fa-bullseye text-amber-500 mr-1"></i> ゴール:</b> {{ $sprint->goal }}
                    </div>
                @endif

                <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between text-xs text-slate-500 gap-2">
                    <div>
                        タスク数: <b class="text-slate-800">{{ $sprint->tasks_count }}</b> 件 &bull;
                        完了ポイント: <b class="text-indigo-600">{{ $sprint->completedPoints() }}</b> / {{ $sprint->totalPoints() }} pt
                    </div>
                    <div class="w-48 bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $sprint->progressPercent() }}%"></div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-slate-200 p-12 text-center text-slate-400 text-xs">
                スプリントがまだ登録されていません。
            </div>
        @endforelse
    </div>

</div>

<!-- スプリント作成モーダル -->
<div id="sprint-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-bold text-slate-900">新規スプリント作成</h3>
            <button onclick="document.getElementById('sprint-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('sprints.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">スプリント名 <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="例: Sprint 3: 決済システム統合" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">スプリントゴール</label>
                <textarea name="goal" rows="2" placeholder="このスプリントで達成したい主要目的" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">開始日 <span class="text-rose-500">*</span></label>
                    <input type="date" name="start_date" value="{{ now()->format('Y-m-d') }}" required class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">終了日 <span class="text-rose-500">*</span></label>
                    <input type="date" name="end_date" value="{{ now()->addWeeks(2)->format('Y-m-d') }}" required class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">ステータス <span class="text-rose-500">*</span></label>
                <select name="status" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                    <option value="planning">Planning (計画中)</option>
                    <option value="active" selected>Active (進行中)</option>
                    <option value="completed">Completed (完了)</option>
                </select>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('sprint-modal').classList.add('hidden')" class="px-4 py-2 text-xs bg-slate-100 rounded-lg text-slate-700">キャンセル</button>
                <button type="submit" class="px-4 py-2 text-xs bg-indigo-600 text-white font-bold rounded-lg shadow-sm">作成する</button>
            </div>
        </form>
    </div>
</div>
@endsection
