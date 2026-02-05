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
        Schema::create('scholars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->string('scholar_number')->unique();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'suspended', 'graduated', 'terminated'])->default('active');
            $table->decimal('current_gwa', 5, 2)->nullable();
            $table->boolean('is_renewable')->default(true);
            $table->text('suspension_reason')->nullable();
            $table->text('termination_reason')->nullable();
            $table->foreignId('contract_signed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('contract_signed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholars');
    }
};
