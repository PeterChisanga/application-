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
        Schema::table('fuels', function (Blueprint $table) {
            $table->decimal('cost', 10, 2)->nullable()->after('litres_added');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuels', function (Blueprint $table) {
            $table->dropColumn('cost');
        });
    }
};
