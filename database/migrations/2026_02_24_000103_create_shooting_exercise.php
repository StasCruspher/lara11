<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shooting_exercise', function (Blueprint $table) {

            $table->unsignedInteger('id', true);

            $table->string('code', 50);
            $table->string('name');

            $table->unsignedInteger('weapon_id');

            $table->text('target_description');
            $table->integer('distance_m');
            $table->integer('ammo_count');

            $table->integer('time_minutes')->nullable();
            $table->integer('time_seconds')->nullable();
            $table->boolean('time_unlimited')->default(false);

            $table->text('shooting_position');
            $table->text('execution_order');

            $table->foreign('weapon_id')
                ->references('id')
                ->on('weapon');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shooting_exercise');
    }
};