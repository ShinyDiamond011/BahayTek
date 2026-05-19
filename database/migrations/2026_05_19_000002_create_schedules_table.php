<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('type', ['research_consultancy', 'product_development', 'general_consultancy', 'any'])->default('any');
            $table->enum('status', ['available', 'booked', 'cancelled'])->default('available');
            $table->foreignId('created_by')->constrained('staff')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
