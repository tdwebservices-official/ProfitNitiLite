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
        Schema::create('chartaccount', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('gl_name')->nullable();
            $table->longText('ledger_desc')->nullable();
            $table->integer('parent_id')->default(0);
            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->string('sub_type')->nullable();
            $table->integer('summery')->default(0);
            $table->string('sub_summery')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('costcenter_id')->nullable();
            $table->string('interbranch')->nullable();
            $table->string('related_party')->nullable();
            $table->string('gst_applicable')->nullable();
            $table->string('tds_applicable')->nullable();
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
        Schema::dropIfExists('chartaccount');
    }
};