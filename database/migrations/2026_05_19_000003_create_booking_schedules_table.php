<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 255);
            $table->string('phone', 20)->nullable();
            $table->string('organization', 255)->nullable();
            $table->unsignedSmallInteger('no_attendees')->default(1);
            $table->string('topic', 500);
            $table->text('description')->nullable();
            $table->boolean('is_research_collab')->default(false);
            $table->string('collab_institution', 255)->nullable();
            $table->string('collab_type', 100)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'declined', 'cancelled', 'completed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('booked_at')->useCurrent();
            $table->timestamps();

            $table->unique('schedule_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_schedules');
    }
};
