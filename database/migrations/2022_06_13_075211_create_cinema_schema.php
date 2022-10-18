<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /** ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different showrooms

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('types', function($table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('prices', function($table) {
            $table->id();
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->double('value');
            $table->timestamps();
        });
        
        Schema::create('movies', function($table) {
            $table->id();
            $table->string('title');
            $table->string('duration');
            $table->string('description');
            $table->string('genre');
            $table->tinyInteger('status')->default(0);
            $table->dateTime('release_date')->defualt(NULL);
            $table->timestamps();
        });        

        Schema::create('show_timings', function($table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->dateTime('time_from');
            $table->dateTime('time_to');
            $table->timestamps();
        });

        Schema::create('auditoriums', function($table) {
            $table->id();
            $table->string('title');
            $table->integer('no_of_seats');
            $table->timestamps();
        });

        Schema::create('seats', function($table) {
            $table->id();
            $table->string('row');
            $table->integer('number');
            $table->integer('auditorium_id')->unsigned();
            $table->foreign('auditorium_id')->references('id')->on('auditoriums')->onDelete('cascade');
            $table->integer('price_id')->unsigned();
            $table->foreign('price_id')->references('id')->on('prices')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('movie_show_timing', function($table) {
            $table->id();
            $table->integer('movie_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->integer('show_timing_id')->unsigned();
            $table->foreign('show_timing_id')->references('id')->on('show_timings')->onDelete('cascade');
            $table->integer('auditorium_id')->unsigned();
            $table->foreign('auditorium_id')->references('id')->on('auditoriums')->onDelete('cascade');
            $table->double('amount');
            $table->timestamps();
        });       

        Schema::create('reservations', function($table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('seat_id')->unsigned();
            $table->foreign('seat_id')->references('id')->on('seats')->onDelete('cascade');
            $table->integer('movie_show_timing_id')->unsigned();
            $table->foreign('movie_show_timing_id')->references('id')->on('movie_show_timing')->onDelete('cascade');
            $table->double('amount');
            $table->string('state');
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
    }
}
