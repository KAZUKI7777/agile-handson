<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date')->index();
            $table->string('department')->default('デジタル先端技術本部 クラウドアーキテクチャ推進グループ');
            $table->string('user_name');
            $table->string('work_location')->default('リモート');
            $table->string('title');
            $table->text('content');
            $table->text('issues')->nullable();
            $table->text('next_actions')->nullable();
            $table->text('insights')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
