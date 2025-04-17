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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('target_amount', 10, 2);
            $table->enum('frequency', ['weekly', 'monthly']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('goal_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->constrained()->onDelete('cascade');
            $table->decimal('progress_amount', 10, 2)->default(0);
            $table->date('period_start');
            $table->date('period_end');
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_progress');
        Schema::dropIfExists('goals');

    }
};
