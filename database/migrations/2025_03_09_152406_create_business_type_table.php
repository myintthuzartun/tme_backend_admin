<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('business_type', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('business_name_en')->nullable();  
            $table->string('business_name_myan')->nullable();  
            $table->string('business_name_thai')->nullable();  
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_types');
    }
};
