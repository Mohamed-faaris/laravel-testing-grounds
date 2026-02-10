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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->string('user_id');
            $table->string('enrolled_by');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->integer('deadline')->default(0); // in days, 0 means no deadline
            $table->timestamp('enrolled_at')->useCurrent();

            $table->primary(['user_id', 'course_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('enrolled_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
