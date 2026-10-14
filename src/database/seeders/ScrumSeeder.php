<?php

namespace Database\Seeders;

use App\Models\KptItem;
use App\Models\ScrumTask;
use App\Models\Sprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ScrumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        KptItem::truncate();
        ScrumTask::truncate();
        Sprint::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. スプリント1 (Active)
        $sprint1 = Sprint::create([
            'name' => 'Sprint 1: クラウド基盤と日報・タスク連携',
            'goal' => '日報管理とスクラムボードの連携基盤を確立し、開発サイクルを回せる状態にする。',
            'start_date' => '2026-10-05',
            'end_date' => '2026-10-18',
            'status' => 'active',
        ]);

        // 2. スプリント2 (Planning)
        $sprint2 = Sprint::create([
            'name' => 'Sprint 2: 通知連携とパフォーマンスチューニング',
            'goal' => 'Slack通知やオートスケーリングの最適化を完了し、本番運用に耐えうるアーキテクチャにする。',
            'start_date' => '2026-10-19',
            'end_date' => '2026-11-01',
            'status' => 'planning',
        ]);

        // スプリント1のタスク (カンバンに並ぶタスク)
        ScrumTask::create([
            'sprint_id' => $sprint1->id,
            'title' => 'ECSタスクのオートスケーリング遅延改善',
            'description' => 'CPU高負荷時のスケールアウト時間短縮のためCloudWatchアラーム評価期間を1分化',
            'as_a' => 'インフラ担当エンジニア',
            'i_want' => '急なスパイクアクセス時にも即座にコンテナが増設される',
            'so_that' => 'ユーザーへのレスポンス遅延やダウンタイムを防ぐことができる',
            'acceptance_criteria' => "- 評価期間1分のアラーム設定\n- 検証環境での負荷テストで3分以内に増設確認",
            'assignee' => '山田 太郎',
            'story_points' => 3,
            'priority' => 'high',
            'status' => 'done',
        ]);

        ScrumTask::create([
            'sprint_id' => $sprint1->id,
            'title' => '日報管理システムの検索機能実装',
            'description' => '日付指定および期間指定での日報一覧絞り込みロジック',
            'as_a' => 'チームメンバー',
            'i_want' => '特定の日付や期間で日報を素早く探したい',
            'so_that' => '過去の知見や作業履歴を振り返りやすくなる',
            'acceptance_criteria' => "- 日付完全一致検索ができる\n- 期間(From-To)検索ができる",
            'assignee' => '島田',
            'story_points' => 5,
            'priority' => 'high',
            'status' => 'done',
        ]);

        ScrumTask::create([
            'sprint_id' => $sprint1->id,
            'title' => 'スクラムカンバンボードUIの実装',
            'description' => 'To Do, In Progress, Review, Doneの4列でタスクを可視化',
            'as_a' => 'スクラムデベロッパー',
            'i_want' => 'スプリント内の全タスクの進行状況をひと目で把握したい',
            'so_that' => 'ボトルネックを素早く発見しチームで助け合える',
            'acceptance_criteria' => "- 4つのステータス列にタスクが表示される\n- ワンクリックでステータス変更ができる",
            'assignee' => '山田 太郎',
            'story_points' => 5,
            'priority' => 'high',
            'status' => 'in_progress',
        ]);

        ScrumTask::create([
            'sprint_id' => $sprint1->id,
            'title' => 'プランニングポーカー見積もり機能の作成',
            'description' => 'フィボナッチ数列カードを用いたチーム見積もり画面',
            'as_a' => 'スクラムチーム',
            'i_want' => 'オンラインで直感的にストーリーポイントを見積もりたい',
            'so_that' => 'タスクの規模感についての認識合わせがスムーズに進む',
            'acceptance_criteria' => "- フィボナッチカードを選択して決定できる\n- タスクのストーリーポイントが更新される",
            'assignee' => '佐藤 花子',
            'story_points' => 3,
            'priority' => 'medium',
            'status' => 'review',
        ]);

        ScrumTask::create([
            'sprint_id' => $sprint1->id,
            'title' => 'KPTふりかえり機能の作成',
            'description' => 'スプリントレトロスペクティブでKeep, Problem, Tryを共有',
            'as_a' => 'スクラムチーム',
            'i_want' => 'ふりかえりの意見を付箋のように投稿・投票したい',
            'so_that' => '次のスプリントに向けたアクション(Try)を具体化できる',
            'acceptance_criteria' => "- Keep, Problem, Tryを投稿できる\n- 投票(いいね)ができる",
            'assignee' => '島田',
            'story_points' => 3,
            'priority' => 'medium',
            'status' => 'todo',
        ]);

        // ポイント未定のタスク (プランニングポーカー見積もり対象)
        ScrumTask::create([
            'sprint_id' => $sprint1->id,
            'title' => 'Slackへのデイリーサマリー自動通知Webhook連携',
            'description' => '毎夕17時に当日の日報件数と未完了タスク数を通知',
            'as_a' => 'スクラムマスター',
            'i_want' => '夕方に自動で進捗概要がSlackに流れるようにしたい',
            'so_that' => '夕会での確認がスムーズになりリマインドの手間が減る',
            'acceptance_criteria' => "- Webhook経由でメッセージが送信される\n- 失敗時のエラーログ記録",
            'assignee' => null,
            'story_points' => null, // 未見積もり！
            'priority' => 'low',
            'status' => 'todo',
        ]);

        // KPTアイテム
        KptItem::create([
            'sprint_id' => $sprint1->id,
            'type' => 'keep',
            'content' => '毎朝15分のスタンドアップでブロッカーを早期に共有できた。',
            'author' => '山田 太郎',
            'votes' => 4,
        ]);

        KptItem::create([
            'sprint_id' => $sprint1->id,
            'type' => 'keep',
            'content' => 'TDDの実践によりPRレビュー時の不具合が大幅に減った。',
            'author' => '佐藤 花子',
            'votes' => 6,
        ]);

        KptItem::create([
            'sprint_id' => $sprint1->id,
            'type' => 'problem',
            'content' => 'IaCのパラメータ設計で一部ハードコードが残っており検証に時間がかかった。',
            'author' => '山田 太郎',
            'votes' => 3,
        ]);

        KptItem::create([
            'sprint_id' => $sprint1->id,
            'type' => 'try',
            'content' => 'モジュール設計時に環境別変数をTerraform変数として外出し定義する規約を作る。',
            'author' => '島田',
            'votes' => 5,
            'is_actioned' => false,
        ]);

        KptItem::create([
            'sprint_id' => $sprint1->id,
            'type' => 'try',
            'content' => 'プランニングポーカーで意見が割れた際は、最大値と最小値を出した人が理由を説明するルールを徹底する。',
            'author' => '佐藤 花子',
            'votes' => 7,
            'is_actioned' => true,
        ]);
    }
}
