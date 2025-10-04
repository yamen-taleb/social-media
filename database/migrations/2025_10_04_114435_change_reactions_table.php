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
        Schema::table('reactions', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->renameColumn('post_id', 'model_id');
            $table->string('model_type')->after('model_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reactions', function (Blueprint $table) {
            $table->dropColumn('model_type');
            $table->renameColumn('model_id', 'post_id');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }
};
