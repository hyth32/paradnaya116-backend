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
            $table->integer('rental_application_id');
            $table->foreign('rental_application_id', 'fk-rental-application-1')
                ->references('id')
                ->on('rental_applications');
            $table->integer('product_id');
            $table->foreign('product_id', 'fk-rental-product-1')
                ->references('id')
                ->on('products');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_application_products');
    }
};
