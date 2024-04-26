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
        Schema::table('callbacks', function (Blueprint $table) {
            $table->integer('total')->nullable()->after('via');
            $table->integer('discount')->nullable()->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('callbacks', function (Blueprint $table) {
            $table->dropColumn('total');
            $table->dropColumn('discount');
        });
    }
};
