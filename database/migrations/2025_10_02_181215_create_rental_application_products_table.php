<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_application_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
            
            $table->unique(['rental_application_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_application_products');
    }
};
