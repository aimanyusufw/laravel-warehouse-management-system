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
        Schema::create('picking_waves', function (Blueprint $table) {
            $table->id();
            $table->string('wave_number')->unique();
            $table->enum('status', ['Draft', 'Active', 'Completed'])->default('Draft');
            $table->foreignId('picker_user_id')->nullable()->constrained('users'); // Staff yang ditugaskan
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picking_waves');
    }
};
