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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->string('hsn')->nullable();
            $table->string('brand')->nullable();
            $table->integer('vendor_id')->default(0);
            $table->string('unitofmeasure')->nullable();
            $table->float('reorderpoint')->default(0);
            $table->float('eoq')->default(0);
            $table->float('moq')->default(0);
            $table->float('maxstocklevel')->default(0);
            $table->float('safetystocklevel')->default(0);
            $table->float('leadtime')->default(0);
            $table->float('markuppercentage')->default(0);
            $table->float('profitmargin')->default(0);
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
        Schema::dropIfExists('inventory');
    }
};