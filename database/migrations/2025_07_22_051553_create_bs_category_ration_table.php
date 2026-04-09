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
        Schema::create('bs_category_ratio', function (Blueprint $table) {
            $table->id();
            $table->integer('industry_id')->default(0);       
            $table->longText('ratio_name')->nullable();
            $table->string('default_ratio')->nullable();
            $table->float('min_ratio')->default(0);
            $table->float('max_ratio')->default(0);
            $table->float('wt')->default(0);
            $table->float('strong')->default(0);
            $table->float('steady')->default(0);
            $table->float('shaky')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bs_category_ratio');
    }
};