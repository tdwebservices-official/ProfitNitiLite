<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialCalculationsTable extends Migration
{
    public function up()
    {
        Schema::create('financial_calculations', function (Blueprint $table) {
            $table->id();
            $table->string('user_session')->nullable();
            $table->string('calculation_type');
            $table->json('input_data');
            $table->json('result_data');
            $table->timestamps();

            $table->index(['user_session', 'calculation_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_calculations');
    }
}