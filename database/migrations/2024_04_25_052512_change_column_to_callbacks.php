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
            $table->string('quote')->nullable()->change();
            $table->date('enquiry_date')->nullable()->change();
            $table->date('booking_date')->nullable()->change();
            $table->string('job_status')->nullable()->change();
            $table->string('pick_up')->nullable()->change();
            $table->string('drop_off')->nullable()->change();
            $table->string('customer_name')->after('id')->nullable();
            $table->string('customer_phone')->after('customer_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('callbacks', function (Blueprint $table) {
            $table->string('quote')->nullable(false)->change();
            $table->date('enquiry_date')->nullable(false)->change();
            $table->date('booking_date')->nullable(false)->change();
            $table->string('job_status')->nullable(false)->change();
            $table->string('pick_up')->nullable(false)->change();
            $table->string('drop_off')->nullable(false)->change();
            $table->dropColumn('customer_name');
            $table->dropColumn('customer_phone');
        });
    }
};
