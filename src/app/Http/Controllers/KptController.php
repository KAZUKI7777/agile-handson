<?php

namespace App\Http\Controllers;

use App\Models\KptItem;
use Illuminate\Http\Request;

class KptController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sprint_id' => 'nullable|exists:sprints,id',
            'type' => 'required|in:keep,problem,try',
            'content' => 'required|string',
            'author' => 'nullable|string|max:100',
        ]);

        KptItem::create($validated);

        return back()->with('success', strtoupper($validated['type']) . ' を追加しました。');
    }

    public function vote(KptItem $item)
    {
        $item->increment('votes');
        return back()->with('success', '投票しました！');
    }

    public function toggleAction(KptItem $item)
    {
        $item->update(['is_actioned' => !$item->is_actioned]);
        return back()->with('success', 'ステータスを更新しました。');
    }

    public function destroy(KptItem $item)
    {
        $item->delete();
        return back()->with('success', 'ふりかえりアイテムを削除しました。');
    }
}
