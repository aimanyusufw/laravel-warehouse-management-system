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
        Schema::create('receipt_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->constrained('production_receipts');
            $table->foreignId('product_id')->constrained('products');
            $table->string('batch_number');
            $table->integer('quantity_received');
            $table->enum('qc_status', ['Pending', 'Passed', 'Failed'])->default('Pending');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['receipt_id', 'product_id', 'batch_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_details');
    }
};
