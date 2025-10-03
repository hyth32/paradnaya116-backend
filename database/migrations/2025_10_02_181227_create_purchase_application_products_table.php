<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_application_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
            
            $table->unique(['purchase_application_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_application_products');
    }
};
