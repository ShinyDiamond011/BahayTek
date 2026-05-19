<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('prod_name', 255);
            $table->text('prod_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock_qty')->default(0);
            $table->enum('stock_level', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock');
            $table->string('image_url', 500)->nullable();
            $table->string('category', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamp('added_at')->useCurrent();
            $table->timestamps();

            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
