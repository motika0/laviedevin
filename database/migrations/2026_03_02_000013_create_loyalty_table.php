<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loyalty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->integer('points')->default(0);
            $table->integer('total_earned')->default(0);
            $table->enum('level', ['бронза', 'серебро', 'золото'])->default('бронза');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loyalty');
    }
};