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
            // Drop the existing 'location' column
            $table->dropColumn('location');

            // Add the new 'location_id' column
            $table->unsignedBigInteger('location_id')->default(1)->after('job_status'); // Add the new column after 'job_status'
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade'); // Add foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('callbacks', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['location_id']);
            
            // Drop the 'location_id' column
            $table->dropColumn('location_id');

            // Recreate the 'location' column
            $table->string('location')->after('job_status');
        });
    }
};
