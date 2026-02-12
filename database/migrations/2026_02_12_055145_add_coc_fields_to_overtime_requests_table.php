<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('overtime_requests', function (Blueprint $table) {

            $table->integer('total_earned_cocs')->nullable()->after('earned_hours');
            $table->integer('used_cocs')->nullable()->after('total_earned_cocs');
            $table->integer('remaining_cocs')->nullable()->after('used_cocs');
            $table->text('coc_remarks')->nullable()->after('remaining_cocs');

        });
    }

    public function down(): void
    {
        Schema::table('overtime_requests', function (Blueprint $table) {

            $table->dropColumn([
                'total_earned_cocs',
                'used_cocs',
                'remaining_cocs',
                'coc_remarks'
            ]);

        });
    }
};