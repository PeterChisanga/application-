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
        Schema::table('trips', function (Blueprint $table) {
            $table->decimal('loading', 10, 2)->nullable()->after('quantity');
            $table->decimal('council_fee', 10, 2)->nullable()->after('loading');
            $table->decimal('weighbridge', 10, 2)->nullable()->after('council_fee');
            $table->decimal('toll_gate', 10, 2)->nullable()->after('weighbridge');
            $table->decimal('other_expenses', 10, 2)->nullable()->after('toll_gate');
            $table->string('supplier_name')->nullable()->after('other_expenses');
            $table->decimal('gross_weight', 10, 2)->nullable()->after('supplier_name');
            $table->decimal('net_weight', 10, 2)->nullable()->after('gross_weight');
            $table->decimal('tare_weight', 10, 2)->nullable()->after('net_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn([
                'loading',
                'council_fee',
                'weighbridge',
                'toll_gate',
                'other_expenses',
                'supplier_name',
                'gross_weight',
                'net_weight',
                'tare_weight'
            ]);
        });
    }
};