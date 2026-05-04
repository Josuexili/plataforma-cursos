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
        Schema::table('courses', function (Blueprint $table) {
            $table->softDeletes();
            $table->index(['nivell', 'deleted_at']);
            $table->index(['user_id', 'deleted_at']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->softDeletes();
            $table->index(['course_id', 'ordre']);
        });

        Schema::table('course_user', function (Blueprint $table) {
            $table->dropUnique('course_user_user_id_course_id_unique');
            $table->softDeletes();
            $table->index(['user_id', 'course_id']);
            $table->index(['course_id', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_user', function (Blueprint $table) {
            $table->dropIndex('course_user_user_id_course_id_index');
            $table->dropIndex('course_user_course_id_deleted_at_index');
            $table->dropSoftDeletes();
            $table->unique(['user_id', 'course_id']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropIndex('lessons_course_id_ordre_index');
            $table->dropSoftDeletes();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('courses_nivell_deleted_at_index');
            $table->dropIndex('courses_user_id_deleted_at_index');
            $table->dropSoftDeletes();
        });
    }
};
