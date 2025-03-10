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

            // Level Description in Multiple Languages
            $table->text('level_en')->nullable();  
            $table->text('level_myan')->nullable();  
            $table->text('level_thai')->nullable();  
            // Benefits in Multiple Languages
            $table->text('level_description_en')->nullable();  
            $table->text('level_description_myan')->nullable();  
            $table->text('level_description_thai')->nullable();  
            // Benefits in Multiple Languages
            $table->text('benefits_en')->nullable();
            $table->text('benefits_myan')->nullable();
            $table->text('benefits_thai')->nullable();  
            
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_level');
    }
}
