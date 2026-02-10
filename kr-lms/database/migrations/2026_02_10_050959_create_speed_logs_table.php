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
        Schema::create('speed_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreignId('content_id')->constrained('contents')->onDelete('cascade');
            $table->enum('event', ['pause', 'stop', 'start']);
            $table->decimal('speed', 4, 2)->nullable();
            $table->timestamp('logged_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id', 'speed_logs_user_id_idx');
            $table->index('content_id', 'speed_logs_content_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speed_logs');
    }
};
