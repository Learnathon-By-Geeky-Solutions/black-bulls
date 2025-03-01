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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->string('thumbnail');
            $table->decimal('rating', 2, 1)->default(0.0);
            $table->decimal('price', 8, 2);
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive', 'draft']);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
