<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shooting_score_exercise', function (Blueprint $table) {

            $table->unsignedInteger('id', true);

            $table->unsignedInteger('shooting_score_id');
            $table->unsignedInteger('shooting_exercise_id');

            $table->foreign('shooting_score_id')
                ->references('id')
                ->on('shooting_score')
                ->cascadeOnDelete();

            $table->foreign('shooting_exercise_id')
                ->references('id')
                ->on('shooting_exercise');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shooting_score_exercise');
    }
};