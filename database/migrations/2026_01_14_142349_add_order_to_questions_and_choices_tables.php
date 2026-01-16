<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions', 'order')) {
                $table->unsignedInteger('order')->default(0);
            }
        });

        Schema::table('choices', function (Blueprint $table) {
            if (!Schema::hasColumn('choices', 'order')) {
                $table->unsignedInteger('order')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions', 'order')) {
                $table->dropColumn('order');
            }
        });

        Schema::table('choices', function (Blueprint $table) {
            if (Schema::hasColumn('choices', 'order')) {
                $table->dropColumn('order');
            }
        });
    }
};
