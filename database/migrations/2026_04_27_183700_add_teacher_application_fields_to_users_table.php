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
        Schema::table('users', function (Blueprint $table) {
            $table->string('teacher_application_status')
                ->default('none')
                ->after('is_admin');
            $table->timestamp('teacher_requested_at')
                ->nullable()
                ->after('teacher_application_status');
            $table->timestamp('teacher_reviewed_at')
                ->nullable()
                ->after('teacher_requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'teacher_application_status',
                'teacher_requested_at',
                'teacher_reviewed_at',
            ]);
        });
    }
};
