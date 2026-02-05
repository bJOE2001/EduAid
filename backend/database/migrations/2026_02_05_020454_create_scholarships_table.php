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
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->enum('type', ['merit', 'need-based', 'sports', 'academic', 'other']);
            $table->decimal('budget', 15, 2);
            $table->decimal('allowance_per_month', 10, 2)->nullable();
            $table->integer('max_slots');
            $table->integer('current_slots')->default(0);
            $table->date('application_start');
            $table->date('application_end');
            $table->json('qualifications')->nullable(); // min_gwa, max_income, etc.
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
