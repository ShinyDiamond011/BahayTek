<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('var_name', 255);
            $table->text('description')->nullable();
            $table->text('specification')->nullable();
            $table->decimal('price_modifier', 10, 2)->default(0.00);
            $table->unsignedInteger('stock_qty')->default(0);
            $table->timestamp('date_added')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
