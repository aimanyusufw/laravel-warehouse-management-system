<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('location_id')->constrained('locations');
            $table->string('batch_number');
            $table->integer('quantity');
            $table->date('expiration_date')->nullable();
            $table->string('pallet_id')->unique(); // Kunci untuk QR Code
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['product_id', 'batch_number', 'location_id']);
        });
    }
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
};
