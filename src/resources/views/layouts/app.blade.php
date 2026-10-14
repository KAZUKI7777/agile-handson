<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ScrumFlow') - アジャイル・スクラム開発支援</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col font-sans antialiased">
    <!-- グローバルナビゲーション -->
    <header class="bg-slate-900 border-b border-slate-800 sticky top-0 z-40 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- ロゴ & サービス名 -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('scrum.dashboard') }}" class="flex items-center space-x-2.5 text-white hover:text-indigo-400 transition">
                        <span class="w-9 h-9 bg-gradient-to-tr from-indigo-500 to-sky-400 rounded-lg flex items-center justify-center text-white text-lg font-bold shadow-md shadow-indigo-500/20">
                            <i class="fa-solid fa-bolt"></i>
                        </span>
                        <div>
                            <span class="font-extrabold text-lg tracking-tight block text-white leading-tight">ScrumFlow</span>
                            <span class="text-[10px] text-slate-400 tracking-wider font-medium uppercase block">Agile Companion</span>
                        </div>
                    </a>

                    <!-- ナビゲーションリンク -->
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('scrum.dashboard') }}" class="px-3 py-1.5 rounded-md text-xs font-semibold transition {{ request()->routeIs('scrum.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            <i class="fa-solid fa-chart-pie mr-1"></i> ダッシュボード
                        </a>
                        <a href="{{ route('scrum.board') }}" class="px-3 py-1.5 rounded-md text-xs font-semibold transition {{ request()->routeIs('scrum.board') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            <i class="fa-solid fa-table-columns mr-1"></i> カンバンボード
                        </a>
                        <a href="{{ route('scrum.poker') }}" class="px-3 py-1.5 rounded-md text-xs font-semibold transition {{ request()->routeIs('scrum.poker') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            <i class="fa-solid fa-dice mr-1"></i> ポーカー見積もり
                        </a>
                        <a href="{{ route('scrum.kpt') }}" class="px-3 py-1.5 rounded-md text-xs font-semibold transition {{ request()->routeIs('scrum.kpt') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            <i class="fa-solid fa-comments mr-1"></i> KPTふりかえり
                        </a>
                        <a href="{{ route('sprints.index') }}" class="px-3 py-1.5 rounded-md text-xs font-semibold transition {{ request()->routeIs('sprints.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            <i class="fa-solid fa-flag-checkered mr-1"></i> スプリント管理
                        </a>
                    </nav>
                </div>

                <!-- 右側アクション (日報へのリンクなど) -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('reports.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium border border-slate-700 bg-slate-800/80 text-slate-200 hover:bg-slate-700 transition">
                        <i class="fa-solid fa-clipboard-list mr-1.5 text-indigo-400"></i> 業務日報管理
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if (session('success'))
            <div class="mb-5 p-3.5 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-800 text-xs font-medium flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 p-3.5 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-800 text-xs shadow-sm">
                <div class="flex items-center space-x-2 mb-1.5 font-bold">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                    <span>入力エラーを確認してください:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-slate-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- フッター -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500 mt-auto">
        <p>ScrumFlow &bull; デジタル先端技術本部 クラウドアーキテクチャ推進グループ</p>
    </footer>

    @yield('scripts')
</body>
</html>
