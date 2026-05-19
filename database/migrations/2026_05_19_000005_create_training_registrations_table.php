<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('training_session_id')->constrained('training_sessions')->cascadeOnDelete();
            $table->enum('registration_status', ['pending', 'confirmed', 'declined', 'cancelled', 'attended'])->default('pending');
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'training_session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_registrations');
    }
};
