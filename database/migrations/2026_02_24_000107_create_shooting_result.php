<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shooting_result', function (Blueprint $table) {

            $table->unsignedInteger('id', true);

            $table->unsignedInteger('shooting_score_id');
            $table->unsignedInteger('participant_id');
            $table->unsignedInteger('final_grade_id')->nullable();

            $table->foreign('shooting_score_id')
                ->references('id')
                ->on('shooting_score');

            $table->foreign('participant_id')
                ->references('id')
                ->on('participant');

            $table->foreign('final_grade_id')
                ->references('id')
                ->on('shooting_grade_requirement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shooting_result');
    }
};