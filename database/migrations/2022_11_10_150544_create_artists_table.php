<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('artist_fullname');
            $table->string('artist_stagename');
            $table->integer('age');
            $table->enum('gender', ['MALE', 'FEMALE'])->default('MALE');
            $table->string('music_genre');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->enum('role', ['DEVS', 'ARTISTS', 'USERS'])->default('USERS');
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
        Schema::dropIfExists('artists');
    }
}
