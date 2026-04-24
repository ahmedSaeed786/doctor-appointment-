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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->text('second_address');
            $table->string('emergency_contact');
            $table->string('dob');
            $table->string('gender');
            $table->string('disability');
            $table->string('disability_type')->nullable();
            $table->longText('clinical_indication');
            $table->string('capability');
            $table->string('representative');
            $table->string('appointment_date');
            $table->string('representative_first_name')->nullable();
            $table->string('representative_last_name')->nullable();
            $table->decimal('total');
            $table->integer('user_id');
            $table->string('promo_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
