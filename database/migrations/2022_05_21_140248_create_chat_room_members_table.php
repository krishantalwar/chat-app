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
        Schema::create('chat_room_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_rooms_id')->nullable();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->timestamps();

            $table->foreign('chat_rooms_id')
                ->references('id')
                ->on('chat_rooms');

            $table->foreign('member_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_room_members');
    }
};