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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('salary_file');
            $table->date('date_filing');
            $table->integer('days_applied');
            $table->boolean('commutation')->default(false);
            $table->string('reason')->nullable();
            $table->string('signature');
            $table->string('leave_type');
            $table->json('leave_details')->nullable();
            $table->text('disapproval_reason')->nullable();
            $table->integer('approved_days_with_pay')->nullable();
            $table->integer('approved_days_without_pay')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('hr_officer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('hr_action_at')->nullable();
            $table->enum('hr_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('admin_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
