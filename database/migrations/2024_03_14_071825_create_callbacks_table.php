<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('callbacks', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('quote')->nullable();
            $table->date('enquiry_date')->nullable();
            $table->date('booking_date')->nullable();
            $table->date('callback_date')->nullable();
            $table->string('job_status')->nullable();
            $table->string('callback_status')->nullable();
            $table->string('pick_up')->nullable();
            $table->string('drop_off')->nullable();
            $table->string('via')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('callbacks');
    }
};
