<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pro_id');
            $table->string('name', 200);
            $table->string('image', 200);
            $table->string('price', 20);
            $table->string('user_id', 10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
