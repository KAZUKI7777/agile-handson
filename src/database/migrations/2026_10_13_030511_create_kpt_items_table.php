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
        Schema::create('kpt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sprint_id')->nullable()->constrained('sprints')->nullOnDelete();
            $table->enum('type', ['keep', 'problem', 'try']); // Keep, Problem, Try
            $table->text('content'); // 内容
            $table->string('author')->nullable(); // 投稿者名
            $table->unsignedInteger('votes')->default(0); // いいね数
            $table->boolean('is_actioned')->default(false); // Tryが実行されたか
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpt_items');
    }
};
