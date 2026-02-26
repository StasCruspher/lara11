<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shooting_result_exercise', function (Blueprint $table) {

            $table->unsignedInteger('id', true);

            $table->unsignedInteger('shooting_result_id');
            $table->unsignedInteger('shooting_exercise_id');
            $table->unsignedInteger('grade_id')->nullable();

            $table->integer('hits')->nullable();
            $table->integer('misses')->nullable();

            $table->foreign('shooting_result_id')
                ->references('id')
                ->on('shooting_result')
                ->cascadeOnDelete();

            $table->foreign('shooting_exercise_id')
                ->references('id')
                ->on('shooting_exercise');

            $table->foreign('grade_id')
                ->references('id')
                ->on('shooting_grade_requirement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shooting_result_exercise');
    }
};