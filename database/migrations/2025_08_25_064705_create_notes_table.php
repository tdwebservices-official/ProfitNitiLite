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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('t_month')->nullable();
            $table->longText('target')->nullable();
            $table->longText('action_steps')->nullable();
            $table->string('person_responsible')->nullable();
            $table->string('target_date')->nullable();
            $table->integer('status')->default(0);
            $table->integer('user_id')->default(0);
            $table->longText('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};