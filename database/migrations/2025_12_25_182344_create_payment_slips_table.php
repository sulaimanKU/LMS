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
        Schema::create('payment_slips', function (Blueprint $table) {
         $table->id();
        $table->foreignId('registration_id')->constrained()->onDelete('cascade');
        $table->string('file_path');
        $table->string('status')->default('pending'); // pending, verified, rejected
        $table->text('admin_notes')->nullable(); // For rejection reasons
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_slips');
    }
};
