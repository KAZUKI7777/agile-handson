@extends('layouts.app')

@section('title', 'KPTふりかえりボード')

@section('content')
<div class="space-y-6">

    <!-- コントロールバー -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center">
                <i class="fa-solid fa-comments text-indigo-600 mr-2"></i> KPT ふりかえりボード
            </h1>

            <form action="{{ route('scrum.kpt') }}" method="GET" class="inline">
                <select name="sprint_id" onchange="this.form.submit()" class="text-xs font-semibold px-3 py-1.5 border border-slate-300 rounded-lg bg-slate-50 text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach ($sprints as $s)
                        <option value="{{ $s->id }}" {{ $sprint && $sprint->id === $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div>
            <button onclick="document.getElementById('kpt-modal').classList.remove('hidden')" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                <i class="fa-solid fa-plus mr-1.5"></i> ふりかえり意見を追加
            </button>
        </div>
    </div>

    <!-- KPT 3カラムレイアウト -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

        <!-- 1. KEEP 列 -->
        <div class="bg-emerald-500/5 rounded-2xl p-4 border border-emerald-500/20 space-y-4">
            <div class="flex items-center justify-between border-b border-emerald-500/20 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-black">K</span>
                    <h2 class="text-sm font-black text-emerald-950 uppercase tracking-wider">Keep (継続・良かった点)</h2>
                </div>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">{{ $keeps->count() }} 件</span>
            </div>

            <div class="space-y-3">
                @forelse ($keeps as $item)
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-emerald-200/60 hover:shadow-md transition space-y-3">
                        <p class="text-xs text-slate-800 leading-relaxed font-medium whitespace-pre-line">{{ $item->content }}</p>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[11px] text-slate-400">
                            <span><i class="fa-solid fa-user mr-1 text-slate-300"></i> {{ $item->author ?? '匿名' }}</span>
                            <div class="flex items-center space-x-2">
                                <form action="{{ route('kpt.vote', $item) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 rounded bg-slate-50 hover:bg-rose-50 text-slate-600 hover:text-rose-600 font-bold transition flex items-center space-x-1">
                                        <i class="fa-solid fa-heart text-rose-500"></i>
                                        <span>{{ $item->votes }}</span>
                                    </button>
                                </form>
                                <form action="{{ route('kpt.destroy', $item) }}" method="POST" onsubmit="return confirm('削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-300 hover:text-rose-500 p-1">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-xs text-slate-400 border-2 border-dashed border-emerald-200 rounded-xl">
                        Keep がまだありません
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 2. PROBLEM 列 -->
        <div class="bg-rose-500/5 rounded-2xl p-4 border border-rose-500/20 space-y-4">
            <div class="flex items-center justify-between border-b border-rose-500/20 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-lg bg-rose-600 text-white flex items-center justify-center text-xs font-black">P</span>
                    <h2 class="text-sm font-black text-rose-950 uppercase tracking-wider">Problem (課題・困りごと)</h2>
                </div>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-800">{{ $problems->count() }} 件</span>
            </div>

            <div class="space-y-3">
                @forelse ($problems as $item)
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-rose-200/60 hover:shadow-md transition space-y-3">
                        <p class="text-xs text-slate-800 leading-relaxed font-medium whitespace-pre-line">{{ $item->content }}</p>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[11px] text-slate-400">
                            <span><i class="fa-solid fa-user mr-1 text-slate-300"></i> {{ $item->author ?? '匿名' }}</span>
                            <div class="flex items-center space-x-2">
                                <form action="{{ route('kpt.vote', $item) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 rounded bg-slate-50 hover:bg-rose-50 text-slate-600 hover:text-rose-600 font-bold transition flex items-center space-x-1">
                                        <i class="fa-solid fa-heart text-rose-500"></i>
                                        <span>{{ $item->votes }}</span>
                                    </button>
                                </form>
                                <form action="{{ route('kpt.destroy', $item) }}" method="POST" onsubmit="return confirm('削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-300 hover:text-rose-500 p-1">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-xs text-slate-400 border-2 border-dashed border-rose-200 rounded-xl">
                        Problem がまだありません
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 3. TRY 列 -->
        <div class="bg-amber-500/5 rounded-2xl p-4 border border-amber-500/20 space-y-4">
            <div class="flex items-center justify-between border-b border-amber-500/20 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-lg bg-amber-600 text-white flex items-center justify-center text-xs font-black">T</span>
                    <h2 class="text-sm font-black text-amber-950 uppercase tracking-wider">Try (次回挑戦・改善)</h2>
                </div>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">{{ $trys->count() }} 件</span>
            </div>

            <div class="space-y-3">
                @forelse ($trys as $item)
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-amber-200/60 hover:shadow-md transition space-y-3">
                        <div class="flex items-start space-x-2">
                            <form action="{{ route('kpt.toggle', $item) }}" method="POST" class="mt-0.5">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" onchange="this.form.submit()" {{ $item->is_actioned ? 'checked' : '' }} class="rounded border-slate-300 text-amber-600 focus:ring-amber-500 cursor-pointer">
                            </form>
                            <p class="text-xs leading-relaxed {{ $item->is_actioned ? 'line-through text-slate-400' : 'font-medium text-slate-800' }} whitespace-pre-line">
                                {{ $item->content }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[11px] text-slate-400">
                            <span class="flex items-center space-x-2">
                                <span><i class="fa-solid fa-user mr-1 text-slate-300"></i> {{ $item->author ?? '匿名' }}</span>
                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold {{ $item->is_actioned ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $item->is_actioned ? '実行済' : '未実行' }}
                                </span>
                            </span>
                            <div class="flex items-center space-x-2">
                                <form action="{{ route('kpt.vote', $item) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 rounded bg-slate-50 hover:bg-rose-50 text-slate-600 hover:text-rose-600 font-bold transition flex items-center space-x-1">
                                        <i class="fa-solid fa-heart text-rose-500"></i>
                                        <span>{{ $item->votes }}</span>
                                    </button>
                                </form>
                                <form action="{{ route('kpt.destroy', $item) }}" method="POST" onsubmit="return confirm('削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-300 hover:text-rose-500 p-1">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-xs text-slate-400 border-2 border-dashed border-amber-200 rounded-xl">
                        Try がまだありません
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

<!-- KPT追加モーダル -->
<div id="kpt-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-bold text-slate-900">ふりかえり意見を追加</h3>
            <button onclick="document.getElementById('kpt-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('kpt.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="sprint_id" value="{{ $sprint ? $sprint->id : '' }}">

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">分類 <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="border rounded-lg p-2.5 text-center cursor-pointer hover:bg-emerald-50 transition has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50">
                        <input type="radio" name="type" value="keep" required checked class="sr-only">
                        <span class="text-xs font-bold text-emerald-800 block">Keep</span>
                        <span class="text-[10px] text-slate-400 block">継続すること</span>
                    </label>
                    <label class="border rounded-lg p-2.5 text-center cursor-pointer hover:bg-rose-50 transition has-[:checked]:border-rose-600 has-[:checked]:bg-rose-50">
                        <input type="radio" name="type" value="problem" required class="sr-only">
                        <span class="text-xs font-bold text-rose-800 block">Problem</span>
                        <span class="text-[10px] text-slate-400 block">課題・反省</span>
                    </label>
                    <label class="border rounded-lg p-2.5 text-center cursor-pointer hover:bg-amber-50 transition has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50">
                        <input type="radio" name="type" value="try" required class="sr-only">
                        <span class="text-xs font-bold text-amber-800 block">Try</span>
                        <span class="text-[10px] text-slate-400 block">次回試すこと</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">内容 <span class="text-rose-500">*</span></label>
                <textarea name="content" required rows="4" placeholder="ふりかえりの意見を具体的に入力してください" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">投稿者名（任意）</label>
                <input type="text" name="author" placeholder="例: 山田 太郎" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('kpt-modal').classList.add('hidden')" class="px-4 py-2 text-xs bg-slate-100 rounded-lg text-slate-700">キャンセル</button>
                <button type="submit" class="px-4 py-2 text-xs bg-indigo-600 text-white font-bold rounded-lg shadow-sm">追加する</button>
            </div>
        </form>
    </div>
</div>
@endsection
