<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('type', ['Picking', 'Bulk', 'QC', 'Staging'])->default('Picking');
            $table->integer('capacity')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->string('aisle')->nullable();
            $table->string('rack')->nullable();
            $table->integer('level')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
