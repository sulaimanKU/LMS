<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('details')->nullable();
            $table->string('short_description', 500)->nullable();
            $table->string('category', 100);
            $table->string('duration', 100)->nullable();
            $table->decimal('price', 10, 2)->default(3000);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('teacher_id', 'fk_teacher')->references('id')->on('teachers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
