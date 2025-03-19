<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum using raw SQL
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('applicant', 'admin', 'accountant', 'hr', 'lab')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('applicant', 'admin')");
    }
};