<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shooting_grade_requirement', function (Blueprint $table) {

            $table->unsignedInteger('id', true);

            $table->unsignedInteger('shooting_exercise_id');

            $table->string('grade_name', 50);
            $table->text('condition_description');

            $table->foreign('shooting_exercise_id')
                ->references('id')
                ->on('shooting_exercise')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shooting_grade_requirement');
    }
};