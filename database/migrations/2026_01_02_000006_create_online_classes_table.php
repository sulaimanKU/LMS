<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('online_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('meeting_link');
            $table->string('meeting_id')->nullable();
            $table->string('meeting_password')->nullable();
            $table->date('class_date');
            $table->time('start_time');
            $table->integer('duration')->nullable();
            $table->enum('status', ['upcoming', 'live', 'finished', 'cancelled'])->default('upcoming');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('online_classes');
    }
};
