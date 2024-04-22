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
            $table->foreignId('customer_id')->constrained()->casecadeOnDelete();
            $table->string('quote');
            $table->date('enquiry_date');
            $table->date('booking_date');
            $table->date('callback_date')->nullable();
            $table->string('job_status');
            $table->string('callback_status')->nullable();
            $table->string('pick_up');
            $table->string('drop_off');
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
