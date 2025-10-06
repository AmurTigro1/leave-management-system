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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('vacation_leave_balance', 8, 3)->default(0)->change();
            $table->decimal('sick_leave_balance', 8, 3)->default(0)->change();
            $table->decimal('mandatory_leave_balance', 8, 3)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('users', function (Blueprint $table) {
            $table->decimal('vacation_leave_balance', 8, 2)->change();
            $table->decimal('sick_leave_balance', 8, 2)->change();
            $table->decimal('mandatory_leave_balance', 8, 2)->change();
        });
    }
};
