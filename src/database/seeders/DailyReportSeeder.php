<?php

namespace Database\Seeders;

use App\Models\DailyReport;
use Illuminate\Database\Seeder;

class DailyReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DailyReport::truncate();

        DailyReport::create([
            'report_date' => '2026-10-13',
            'department' => 'デジタル先端技術本部 クラウドアーキテクチャ推進グループ',
            'user_name' => '山田 太郎',
            'work_location' => 'リモート',
            'title' => 'スクラムデベロッパー研修受講（前半）',
            'content' => "- スクラムフレームワークの基礎概念およびデベロッパーの役割・責任の整理\n- ユーザーストーリーの分解、バックログの見積もり手法（プランニングポーカー等）の実践\n- ペアプログラミング、TDDをはじめとするアジャイルエンジニアリングプラクティスの演習 (進捗率: 50%)",
            'issues' => '特になし。順調に演習を進行中。',
            'next_actions' => "1. [優先度: 高] スクラムデベロッパー研修 後半パート受講（修了目標）\n2. [優先度: 中] 学びの整理およびチーム展開用メモのドラフト作成",
            'insights' => 'スクラムにおける開発者は自律的にDefinition of Doneを遵守し、チームでゴール達成に向かう姿勢が重要であることを再確認しました。実務プロジェクトへの還元ポイントを整理したいです。',
        ]);

        DailyReport::create([
            'report_date' => '2026-10-12',
            'department' => 'デジタル先端技術本部 クラウドアーキテクチャ推進グループ',
            'user_name' => '山田 太郎',
            'work_location' => '出社',
            'title' => 'ECSオートスケーリング遅延不具合調査と修正PR作成',
            'content' => "- CPU使用率急上昇時のECSタスクスケールアウト遅延事象を調査\n- CloudWatchアラーム評価期間が5分になっていたことが要因と特定\n- 評価期間を1分に変更し検証環境で負荷テスト実施、正常にスケールアウトを確認\n- 修正PR #42 を作成",
            'issues' => 'IaCモジュール側でアラーム設定値がハードコード気味になっている点。',
            'next_actions' => "1. [優先度: 高] PR #42 レビュー対応・マージ\n2. [優先度: 中] 次期コンテナ移行計画書ドラフト作成",
            'insights' => 'IaCのパラメータ設計において、環境差分やワークロード特性に応じた調整ができる柔軟性を持たせる重要性を実感しました。',
        ]);

        DailyReport::create([
            'report_date' => '2026-10-09',
            'department' => 'デジタル先端技術本部 クラウドアーキテクチャ推進グループ',
            'user_name' => '佐藤 花子',
            'work_location' => 'リモート',
            'title' => 'Kubernetesクラスタ監視スタック（Prometheus/Grafana）検証',
            'content' => "- 検証環境EKSクラスタへのPrometheus Operatorデプロイ\n- Grafanaダッシュボードでのノード/Podリソースモニタリング設定\n- アラートルール（ノード高負荷、Pod CrashLoopBackOff）の動作検証完了",
            'issues' => 'Slack通知のWebhook URL権限設定で情シスへ申請中（週明け承認予定）。',
            'next_actions' => '1. Slack通知連携のテスト確認\n2. 監視設計ドキュメントの更新',
            'insights' => 'Helm chartを用いた監視スタックの構築は迅速でしたが、不要なメトリクスのスクレイピングを抑えるチューニングが今後のコスト最適化に必要です。',
        ]);
    }
}
