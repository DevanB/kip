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
        Schema::create('dr_test_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dr_test_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->dateTime('started_at');
            $table->dateTime('finished_at');
            $table->integer('duration_minutes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dr_test_phases');
    }
};
