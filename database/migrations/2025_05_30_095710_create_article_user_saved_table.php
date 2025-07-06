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
        Schema::create('article_user_saved', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');     // kolom user_id dengan foreign key
            $table->foreignId('article_id')->constrained()->onDelete('cascade');  // kolom article_id dengan foreign key
            $table->timestamps();

            $table->unique(['user_id', 'article_id']);  // optional: agar kombinasi unik
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_user_saved');
    }
};
