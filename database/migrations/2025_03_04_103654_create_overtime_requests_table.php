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
        Schema::create('overtime_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date_filed');
            $table->string('position');
            $table->string('office_division');
            
            // Ensure working hours are within the allowed range
            $table->integer('working_hours_applied')->check('working_hours_applied BETWEEN 4 AND 10'); 

            $table->date('inclusive_date_start')->index();
            $table->date('inclusive_date_end');
            $table->integer('approved_days')->nullable();
            $table->text('disapproval_reason')->nullable();
            $table->integer('earned_hours')->default(0);

            // Weekend & Holiday Rules
            $table->tinyInteger('is_weekend')->default(0); // 0 = No, 1 = Yes
            $table->tinyInteger('is_holiday')->default(0); // 0 = No, 1 = Yes
            $table->decimal('overtime_rate', 3, 2)->default(1.0); // Default = 1.0, Weekend = 1.5, Holiday = 2.0

            // Overtime Limitations
            $table->decimal('distance_km', 8, 2)->nullable()->check('distance_km <= 50'); // Limit to 50km
            $table->integer('continuous_days_count')->default(0); // Track consecutive overtime days
            $table->date('week_start_date')->nullable(); // Weekly tracking
            $table->integer('total_weekly_hours')->default(0)->check('total_weekly_hours <= 40'); // Weekly max limit

            // Approvals & Status
            $table->foreignId('hr_officer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('supervisor_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('hr_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'waiting_for_supervisor'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_requests');
    }
};
