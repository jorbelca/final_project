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
        Schema::create('support', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('questioner_id');
            $table->unsignedBigInteger('answerer_id')->nullable();
            $table->text('question');
            $table->text('answer')->nullable();
            $table->timestamp('response_date')->nullable();

            $table->foreign('questioner_id')->references('id')->on('users');
            $table->foreign('answerer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support');
    }
};
