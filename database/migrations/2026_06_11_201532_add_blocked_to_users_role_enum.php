<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// The base users migration was updated to include the 'blocked' role, but the
// existing database column was never altered, so blocking a user truncates the
// value and throws a QueryException. This brings the live column in sync.
return new class extends Migration
{
    public function up(): void
    {
        // added to suppoert SQLlite, which doesn't support altering ENUMs, but MySQL does
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('registered', 'admin', 'blocked') NOT NULL DEFAULT 'registered'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('registered', 'admin') NOT NULL DEFAULT 'registered'");
        }
    }
};
