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
        Schema::create('purchase_register', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('invoice_date')->nullable();
            $table->integer('vendor_id')->default(0);
            $table->string('gstin')->nullable();
            $table->integer('vendor_type')->default(0);
            $table->integer('purchase_type')->default(0);
            $table->integer('branch_id')->default(0);
            $table->longText('item_desc')->nullable();
            $table->string('hsn')->nullable();
            $table->float('qty')->default(0);
            $table->float('rate')->default(0);
            $table->float('amount')->default(0);
            $table->float('discount')->default(0);
            $table->float('tax_val')->default(0);
            $table->float('gst')->default(0);
            $table->float('cgst')->default(0);
            $table->float('sgst')->default(0);
            $table->float('igst')->default(0);
            $table->float('total_invoice')->default(0);
            $table->string('payment_terms')->nullable();
            $table->string('due_date')->nullable();
            $table->integer('payment_status')->default(0);            
            $table->string('payment_date')->nullable();
            $table->string('mode_payment')->nullable();
            $table->longText('remarks')->nullable();            
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
        Schema::dropIfExists('purchase_register');
    }
};
