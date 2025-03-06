<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('brand_logo');
            $table->text('file_path');
            $table->string('business_name');

            // Foreign key for business_type
            $table->unsignedBigInteger('b_id');
            $table->foreign('b_id')->references('id')->on('business_types')->onDelete('cascade')->onUpdate('cascade');

            $table->string('brn');
            $table->string('tin');
            $table->string('country');
            $table->string('address');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('website')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('status')->default('pending');

            // Foreign key for vendor_registers table
            $table->unsignedBigInteger('v_id');
            $table->foreign('v_id')->references('id')->on('vendor_level')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });

    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor');
    }
};
