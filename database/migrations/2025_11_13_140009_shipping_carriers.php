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
        Schema::create('shipping_carriers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('service_name')->nullable();
            $table->string('api_key')->nullable();
            $table->string('integration_endpoint')->nullable();
            $table->time('cut_off_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('base_rate', 10, 2)->default(0);
            $table->decimal('min_weight', 8, 3)->default(0);
            $table->decimal('max_weight', 8, 3)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_carriers');
    }
};
