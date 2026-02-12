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
        Schema::table('leaves', function (Blueprint $table) {
            $table->decimal('vacation_balance_before', 8, 3)
                  ->nullable()
                  ->change();

            $table->decimal('sick_balance_before', 8, 3)
                  ->nullable()
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            //
            $table->decimal('vacation_balance_before', 8, 2)
                  ->nullable()
                  ->change();

            $table->decimal('sick_balance_before', 8, 2)
                  ->nullable()
                  ->change();
        });
    }
};
