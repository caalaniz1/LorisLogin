<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('users', function($table){
                $table->increments('id');
                $table->string('username' , '20')->unique();
                $table->string('password' , '256');
                $table->smallInteger('privileges')->default(0);
                
                //To user local profile
                $table->integer('local_profile_id')->unsigned()
                        ->references('id')->on('local_profiles')->nullable();
                
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
		Schema::dropIfExists('users');
	}

}
