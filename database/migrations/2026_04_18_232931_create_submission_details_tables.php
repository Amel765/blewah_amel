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
        Schema::create('submission_comparisons', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->foreignId('criteria_id_1')->constrained('criteria')->onDelete('cascade');
            $table->foreignId('criteria_id_2')->constrained('criteria')->onDelete('cascade');
            $table->double('value');
            $table->timestamps();
        });

        Schema::create('submission_scores', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->foreignId('alternative_id')->constrained('alternatives')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->double('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_scores');
        Schema::dropIfExists('submission_comparisons');
    }
};
