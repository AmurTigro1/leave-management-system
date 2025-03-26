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
        Schema::create('h_r_supervisors', function (Blueprint $table) {
            $table->id();
            $table->string('supervisor_name');
            $table->string('hr_name');
            $table->string('supervisor_signature');
            $table->string('hr_signature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_r_supervisors');
    }
};
