<?php

namespace Tests\Feature;

use App\Models\DailyReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_reports_index(): void
    {
        $response = $this->get('/reports');
        $response->assertStatus(200);
        $response->assertSee('日報一覧');
    }

    public function test_can_filter_reports_by_exact_date(): void
    {
        $targetReport = DailyReport::create([
            'report_date' => '2026-10-13',
            'department' => 'デジタル先端技術本部 クラウドアーキテクチャ推進グループ',
            'user_name' => 'テスト太郎',
            'work_location' => 'リモート',
            'title' => 'ターゲット日報',
            'content' => '業務内容のテスト',
        ]);

        $otherReport = DailyReport::create([
            'report_date' => '2026-10-10',
            'department' => 'デジタル先端技術本部 クラウドアーキテクチャ推進グループ',
            'user_name' => 'テスト次郎',
            'work_location' => '出社',
            'title' => '過去の日報',
            'content' => '過去の業務内容',
        ]);

        $response = $this->get('/reports?date=2026-10-13');

        $response->assertStatus(200);
        $response->assertSee('ターゲット日報');
        $response->assertDontSee('過去の日報');
    }

    public function test_can_filter_reports_by_date_range(): void
    {
        DailyReport::create([
            'report_date' => '2026-10-05',
            'department' => 'デジタル先端技術本部 クラウドアーキテクチャ推進グループ',
            'user_name' => '範囲外',
            'work_location' => '出社',
            'title' => '範囲外の日報',
            'content' => '内容',
        ]);

        DailyReport::create([
            'report_date' => '2026-10-12',
            'department' => 'デジタル先端技術本部 クラウドアーキテクチャ推進グループ',
            'user_name' => '範囲内',
            'work_location' => 'リモート',
            'title' => '範囲内の日報',
            'content' => '内容',
        ]);

        $response = $this->get('/reports?date_from=2026-10-10&date_to=2026-10-15');

        $response->assertStatus(200);
        $response->assertSee('範囲内の日報');
        $response->assertDontSee('範囲外の日報');
    }

    public function test_can_create_a_daily_report(): void
    {
        $reportData = [
            'report_date' => '2026-10-13',
            'department' => 'デジタル先端技術本部 クラウドアーキテクチャ推進グループ',
            'user_name' => '島田',
            'work_location' => 'ハイブリッド',
            'title' => '研修受講',
            'content' => 'スクラム研修を受講しました。',
            'issues' => '特になし',
            'next_actions' => '明日後半を受講予定',
            'insights' => '学びが多かったです。',
        ];

        $response = $this->post('/reports', $reportData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('daily_reports', [
            'title' => '研修受講',
            'user_name' => '島田',
        ]);
    }
}
