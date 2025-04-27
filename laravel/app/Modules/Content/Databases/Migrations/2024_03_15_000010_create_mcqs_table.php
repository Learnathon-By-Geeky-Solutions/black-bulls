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
        Schema::create('mcqs', function (Blueprint $table) {
            $table->id();
            $table->morphs('mcqable');
            $table->text('question');
            $table->json('options');
            $table->string('correct_answer');
            $table->longText('explanation')->nullable();
            $table->unsignedInteger('points')->default(1);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcqs');
    }
};
