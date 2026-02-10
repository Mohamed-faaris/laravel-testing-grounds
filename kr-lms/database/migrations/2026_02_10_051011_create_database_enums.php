<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create enums for PostgreSQL
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("CREATE TYPE roles AS ENUM ('superAdmin', 'admin', 'manager', 'staff')");
            DB::statement("CREATE TYPE college AS ENUM ('krce', 'krct', 'mkce')");
            DB::statement("CREATE TYPE department AS ENUM ('CSE', 'EEE', 'ECE', 'AI', 'AIDS')");
            DB::statement("CREATE TYPE video_events AS ENUM ('pause', 'stop', 'start')");
            DB::statement("CREATE TYPE notifications_status AS ENUM ('active', 'viewed', 'deleted')");
            DB::statement("CREATE TYPE content_type AS ENUM ('video', 'article', 'ppt')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TYPE IF EXISTS roles');
            DB::statement('DROP TYPE IF EXISTS college');
            DB::statement('DROP TYPE IF EXISTS department');
            DB::statement('DROP TYPE IF EXISTS video_events');
            DB::statement('DROP TYPE IF EXISTS notifications_status');
            DB::statement('DROP TYPE IF EXISTS content_type');
        }
    }
};
