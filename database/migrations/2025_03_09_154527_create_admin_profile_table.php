<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_profile', function (Blueprint $table) {
            $table->id();
            // Creating the foreign key constraint
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->unique(); // Ensures one-to-one relationship
            $table->longText('about'); // Allows large text storage
            $table->string('company');
            $table->string('job');
            $table->string('country');
            $table->string('address');
            $table->string('phone');
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_profile');
    }
};
