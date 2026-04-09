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
        Schema::table('trialbalance', function (Blueprint $table) {
            $table->integer('dr_cr')->default(0)->after('credit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trialbalance', function (Blueprint $table) {
            $table->dropColumn(['dr_cr']);
        });
    }
};