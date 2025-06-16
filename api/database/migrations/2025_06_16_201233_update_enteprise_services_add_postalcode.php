<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('enterprise_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprise_id')->constrained('enterprises')->onDelete('cascade');
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('type');
            $table->string('postalCode');
            $table->string('street');
            $table->string('number');
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state');
            $table->string('phone');
            $table->string('email');
            $table->text('additional')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('enterprise_services');
    }
};
