<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorLevelTable extends Migration
{
    public function up()
    {
        Schema::create('vendor_level', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('vendor_level'); // Vendor level name
            $table->text('level_description')->nullable(); // Description of the level
            $table->text('benefits')->nullable(); // Benefits of the level
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_level');
    }
}