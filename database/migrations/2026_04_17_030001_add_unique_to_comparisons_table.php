<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comparisons', function (Blueprint $table) {
            $table->unique(['criteria_id_1', 'criteria_id_2'], 'unique_criteria_pair');
        });
    }

    public function down(): void
    {
        Schema::table('comparisons', function (Blueprint $table) {
            $table->dropUnique('unique_criteria_pair');
        });
    }
};
