<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_application_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['service_application_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_application_services');
    }
};
