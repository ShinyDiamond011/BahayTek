<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');                          // e.g. order_placed, enrollment, booking, login
            $table->string('description');
            $table->nullableMorphs('subject');               // polymorphic: subject_type + subject_id
            $table->unsignedBigInteger('user_id')->nullable();    // customer
            $table->unsignedBigInteger('staff_id')->nullable();   // admin/staff
            $table->json('metadata')->nullable();            // extra context
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('logged_at')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('staff_id')->references('id')->on('staff')->nullOnDelete();
            $table->index(['type', 'logged_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
