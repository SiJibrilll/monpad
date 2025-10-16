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
        Schema::create('week_type_grade_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('week_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('grade_type_id')->constrained()->onDelete('cascade');
            $table->unique(['grade_type_id', 'week_type_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('week_type_grade_types');
    }
};
