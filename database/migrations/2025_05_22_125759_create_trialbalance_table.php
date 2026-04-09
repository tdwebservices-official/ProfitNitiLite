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
        Schema::create('trialbalance', function (Blueprint $table) {
            $table->id();
            $table->string('tbdate')->nullable();
            $table->integer('account_id')->default(0);
            $table->double('opening')->default(0);
            $table->double('closing')->default(0);
            $table->double('debit')->default(0);
            $table->double('credit')->default(0);
            $table->integer('status')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('assign_by')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trialbalance');
    }
};