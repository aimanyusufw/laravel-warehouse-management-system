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
        Schema::create('wave_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('picking_wave_id')->constrained('picking_waves');
            $table->foreignId('order_detail_id')->constrained('order_details');
            $table->foreignId('inventory_id')->constrained('inventories');
            $table->integer('quantity_to_pick');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['picking_wave_id', 'order_detail_id', 'inventory_id'], 'unique_wave_item_inventory');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wave_details');
    }
};
