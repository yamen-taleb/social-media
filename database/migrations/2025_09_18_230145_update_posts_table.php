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
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('deleted_by')->nullable()->change();
            $table->foreignId('group_id')->nullable()->constrained('groups');
            $table->string('slug')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['group_id']);
            $table->dropForeign(['deleted_by']);

            // Then drop the columns
            $table->dropColumn('group_id');
            $table->foreignId('deleted_by')->nullable()->change();
        });
    }
};
