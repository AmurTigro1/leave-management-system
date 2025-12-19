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
        DB::statement("
            ALTER TABLE overtime_requests
            MODIFY admin_status ENUM(
                'pending',
                'Ready for Review',
                'rejected'
            ) DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE overtime_requests
            MODIFY admin_status ENUM(
                'pending',
                'Ready for Review'
            ) DEFAULT 'pending'
        ");
    }
};