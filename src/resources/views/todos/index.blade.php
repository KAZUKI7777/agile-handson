<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo管理アプリ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-4xl font-bold text-gray-800 mb-8 text-center">
            📝 Todo管理アプリ
        </h1>

        <!-- 成功メッセージ -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Todo追加フォーム -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">新しいTodoを追加</h2>

            <form action="{{ route('todos.store') }}" method="POST">
                @csrf
                <div class="flex gap-2">
                    <input
                        type="text"
                        name="title"
                        placeholder="Todoを入力..."
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                    <button
                        type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-6 py-2 rounded-lg transition"
                    >
                        追加
                    </button>
                </div>

                @error('title')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </form>
        </div>

        <!-- Todo一覧 -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                Todo一覧 <span class="text-lg text-gray-500">({{ $todos->count() }}件)</span>
            </h2>

            @if($todos->isEmpty())
                <p class="text-gray-500 text-center py-8">Todoがありません</p>
            @else
                <ul class="space-y-3">
                    @foreach($todos as $todo)
                        <li class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <!-- 完了チェックボックス -->
                            <form action="{{ route('todos.complete', $todo->id) }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-6 h-6 rounded border-2 flex items-center justify-center transition
                                           {{ $todo->completed ? 'bg-green-500 border-green-500' : 'border-gray-300 hover:border-green-500' }}"
                                    {{ $todo->completed ? 'disabled' : '' }}
                                >
                                    @if($todo->completed)
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </button>
                            </form>

                            <!-- Todoタイトル -->
                            <span class="flex-1 {{ $todo->completed ? 'line-through text-gray-400' : 'text-gray-800' }}">
                                {{ $todo->title }}
                            </span>

                            <!-- 削除ボタン -->
                            <form action="{{ route('todos.destroy', $todo->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="text-red-500 hover:text-red-700 font-bold transition"
                                    onclick="return confirm('本当に削除しますか？')"
                                >
                                    削除
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</body>
</html>