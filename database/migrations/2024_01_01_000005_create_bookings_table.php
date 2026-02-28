<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->string('date', 200);
            $table->string('time', 200);
            $table->string('phone', 30);
            $table->text('message');
            $table->string('user_id', 20);
            $table->string('status', 20)->default('Processing');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};