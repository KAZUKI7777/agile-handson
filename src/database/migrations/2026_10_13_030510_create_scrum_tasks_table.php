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
        Schema::create('scrum_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sprint_id')->nullable()->constrained('sprints')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            // ユーザーストーリー形式の属性
            $table->string('as_a')->nullable(); // 〜として
            $table->string('i_want')->nullable(); // 〜したい
            $table->string('so_that')->nullable(); // それにより〜できる
            $table->text('acceptance_criteria')->nullable(); // 受入基準 (DoD)
            $table->string('assignee')->nullable(); // 担当者
            $table->integer('story_points')->nullable(); // ストーリーポイント (1, 2, 3, 5, 8, 13, 21)
            $table->string('priority')->default('medium'); // high, medium, low
            $table->string('status')->default('todo'); // todo, in_progress, review, done
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrum_tasks');
    }
};
