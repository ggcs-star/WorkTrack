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
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            // Basic details
            $table->string('employee_code')->nullable();
            $table->string('photo_path')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('blood_group')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pin_code')->nullable();

            // Employment details
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->foreignId('reporting_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('joining_date')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('work_location')->nullable();
            $table->string('shift')->nullable();

            // Professional details
            $table->text('skills')->nullable();
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->string('total_experience')->nullable();
            $table->string('previous_company')->nullable();
            $table->string('previous_designation')->nullable();
            $table->text('certifications')->nullable();
            $table->string('linkedin_profile')->nullable();

            // Emergency contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_mobile')->nullable();
            $table->string('emergency_contact_alternate_mobile')->nullable();
            $table->text('emergency_contact_address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
