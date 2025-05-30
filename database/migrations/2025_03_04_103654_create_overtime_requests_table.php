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
            $table->integer('working_hours_applied'); 
            $table->string('signature');
            $table->text('inclusive_dates');
            $table->integer('approved_days')->nullable();
            $table->text('disapproval_reason')->nullable();
            $table->integer('earned_hours')->default(0);

            $table->enum('cto_type', ['none', 'halfday_morning', 'halfday_afternoon', 'wholeday'])->default('none');

            // Approvals & Status
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('hr_officer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('supervisor_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('hr_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('admin_status', ['pending', 'Ready for Review'])->default('pending');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'Waiting for Supervisor'])->default('pending');

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
