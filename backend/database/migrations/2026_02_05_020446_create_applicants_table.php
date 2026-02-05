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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('province');
            $table->string('zip_code');
            $table->string('student_number')->nullable();
            $table->string('school_name');
            $table->string('course');
            $table->integer('year_level');
            $table->decimal('gwa', 5, 2)->nullable();
            $table->decimal('family_income', 15, 2);
            $table->integer('family_members');
            $table->text('father_name')->nullable();
            $table->text('father_occupation')->nullable();
            $table->text('mother_name')->nullable();
            $table->text('mother_occupation')->nullable();
            $table->text('guardian_name')->nullable();
            $table->text('guardian_relationship')->nullable();
            $table->text('guardian_contact')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
