<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->enum('status', ['draft', 'pending_review', 'published', 'rejected'])->default('draft')->after('content');
        });

        // Migrate existing is_public data to status
        DB::statement("UPDATE notes SET status = 'published' WHERE is_public = 1");
        DB::statement("UPDATE notes SET status = 'draft' WHERE is_public = 0");

        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('content');
        });

        // Migrate back to is_public
        DB::statement("UPDATE notes SET is_public = 1 WHERE status = 'published'");
        DB::statement("UPDATE notes SET is_public = 0 WHERE status IN ('draft', 'pending_review', 'rejected')");

        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
