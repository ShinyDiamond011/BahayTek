<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('staff')->cascadeOnDelete();
            $table->unsignedInteger('qty_before');
            $table->integer('qty_changed');
            $table->unsignedInteger('qty_after');
            $table->string('reason', 500)->nullable();
            $table->timestamp('date_restocked')->useCurrent();
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();

            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
