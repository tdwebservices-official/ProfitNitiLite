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
        Schema::table('inventory', function (Blueprint $table) {
            $table->float('opening_qty')->default(0)->after('reorderpoint');
            $table->float('opening_rate')->default(0)->after('opening_qty');
            $table->float('opening_value')->default(0)->after('opening_rate');
            
            $table->float('inward_qty')->default(0)->after('opening_value');
            $table->float('inward_rate')->default(0)->after('inward_qty');
            $table->float('inward_value')->default(0)->after('inward_rate');
            
            $table->float('outward_qty')->default(0)->after('inward_value');
            $table->float('outward_rate')->default(0)->after('outward_qty');
            $table->float('outward_value')->default(0)->after('outward_rate');
            
            $table->float('closing_qty')->default(0)->after('outward_value');
            $table->float('closing_rate')->default(0)->after('closing_qty');
            $table->float('closing_value')->default(0)->after('closing_rate');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropColumn(['opening_qty',  'opening_rate', 'opening_value', 'inward_qty','inward_rate',  'inward_value', 'outward_qty', 'outward_rate','outward_value',  'closing_qty', 'closing_rate', 'closing_value']);
        });
    }
};