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
        Schema::create('vendor', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->integer('type')->default(0);
            $table->integer('account_status')->default(0);
            $table->string('primary_contact_name')->nullable();
            $table->string('primary_contact_email')->nullable();
            $table->string('primary_contact_phone')->nullable();
            $table->longText('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->float('credit_limit')->default(0);
            $table->string('payment_terms')->nullable();
            $table->string('currency')->nullable();
            $table->string('gst_no')->nullable();
            $table->string('gst_state_code')->nullable();
            $table->string('gst_state_name')->nullable();
            $table->string('maindistributor')->nullable();
            $table->string('maindealer')->nullable();
            $table->string('mainagent')->nullable();
            $table->string('subdistributor')->nullable();
            $table->string('subdealer')->nullable();
            $table->string('subagent')->nullable();
            $table->string('accpartybankname')->nullable();
            $table->string('accpartybankirfccode')->nullable();
            $table->string('accpartybankactno')->nullable();
            $table->string('accstartdate')->nullable();
            $table->string('accenddate')->nullable();
            $table->string('natureofvendor')->nullable();
            $table->string('tds_category')->nullable();
            $table->string('tds_section')->nullable();
            $table->string('pan_number')->nullable();
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
        Schema::dropIfExists('vendor');
    }
};