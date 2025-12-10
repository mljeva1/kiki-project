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
        Schema::create('logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('created_by')->references('id')->on('users');
            $table->foreignUuid('review_id')->nullable()->references('id')->on('reviews');
            $table->foreignUuid('product_id')->nullable()->references('id')->on('products');
            $table->foreignUuid('category_id')->nullable()->references('id')->on('categories');
            $table->foreignUuid('order_id')->nullable()->references('id')->on('orders');
            $table->foreignUuid('delivery_id')->nullable()->references('id')->on('deliveries');
            $table->enum('action_type', ['create', 'update', 'delete']);
            $table->text('log_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
