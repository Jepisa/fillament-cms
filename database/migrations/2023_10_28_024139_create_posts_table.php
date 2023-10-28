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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(column: 'user_id');
            $table->unsignedBigInteger(column: 'category_id');
            $table->string(column: 'title');
            $table->string(column: 'slug')->unique();
            $table->text(column: 'body');
            $table->string(column: 'image_url')->nullable();

            $table->foreign('user_id')
                ->references(column: 'id')
                ->on(table: 'users')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
