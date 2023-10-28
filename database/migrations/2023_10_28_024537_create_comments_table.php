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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(column: 'post_id');
            // $table->unsignedBigInteger(column: 'user_id');
            $table->string(column: 'body');

            $table->foreign('post_id')
                ->references(column: 'id')
                ->on(table: 'posts')
                ->cascadeOnDelete();
            // $table->foreign('user_id')
            //     ->references(column: 'id')
            //     ->on(table: 'users')
            //     ->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
