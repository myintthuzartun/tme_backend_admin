<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->id();
            $table->decimal('en_currency', 10, 2); // Currency code in English (e.g., USD, MMK, THB)
            $table->decimal('myan_currency', 10, 2); // Currency code in Myanmar (e.g., MMK)
            $table->decimal('thai_exchange_rate', 10, 2); // Exchange rate for Thai currency (e.g., 33.25)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('currency');
    }
};
