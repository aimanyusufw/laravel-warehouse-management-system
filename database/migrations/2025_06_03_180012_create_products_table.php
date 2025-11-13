<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('unit')->default('Pcs');
            $table->integer('min_stock')->default(0);
            $table->decimal('retail_price', 10, 2);
            $table->decimal('weight', 8, 3); // Berat dalam KG (akurasi 3 desimal)
            $table->string('qr_code_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
