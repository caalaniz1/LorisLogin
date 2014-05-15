<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function($table) {
            $table->increments('id');
            $table->string('username', '20')->unique();
            $table->string('email', '100')->unique();
            $table->string('confirmation', '128');
            $table->string('password', '256')->nullable();
            $table->string('remember_token', '100')->nullable();
            $table->smallInteger('privileges')->default(1);

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
    public function down() {
        Schema::dropIfExists('users');
    }

}
