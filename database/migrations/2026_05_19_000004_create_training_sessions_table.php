<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->dateTime('session_datetime');
            $table->unsignedSmallInteger('max_participants');
            $table->string('venue', 255);
            $table->enum('status', ['open', 'ongoing', 'completed', 'cancelled', 'coming_soon'])->default('open');
            $table->foreignId('created_by')->constrained('staff')->cascadeOnDelete();
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
