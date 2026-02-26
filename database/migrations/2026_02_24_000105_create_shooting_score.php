<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shooting_score', function (Blueprint $table) {

            $table->unsignedInteger('id', true);

            $table->unsignedInteger('unit_id');

            $table->date('date');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('unit_id')
                ->references('id')
                ->on('unit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shooting_score');
    }
};