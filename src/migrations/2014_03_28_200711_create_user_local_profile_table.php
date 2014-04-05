<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLocalProfileTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('local_profiles', function($table) {

            $table->increments('id');
            
            $table->string('first_name','20')->nullable();
            $table->string('last_name','20')->nullable();
            $table->string('description','250')->nullable();
            $table->string('gender','20')->nullable();
            $table->string('photo_url','256')->nullable();
            $table->integer('birth_day')->nullable();
            $table->integer('birth_month')->nullable();
            $table->integer('birth_year')->nullable();
            $table->string('email','100')->nullable();
            $table->string('address','150')->nullable();
            $table->string('country','20')->nullable();
            $table->string('city','20')->nullable();
            $table->integer('zip')->nullable();
            $table->timestamps();

            //Owner's id
            $table->integer('user_id')
                    ->nullable()
                    ->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }

}
