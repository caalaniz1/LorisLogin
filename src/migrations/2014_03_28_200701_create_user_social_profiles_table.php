<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSocialProfilesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('social_profiles', function($table) {
            //Name of the provider "Facebook, Google, Twitter, etc..."
            $table->string('provider', '20');

            //Unique Provider's user identifier 
            $table->bigInteger('identifier')
                    ->primary()
                    ->unique()
                    ->unsigned();


            //Owner's id
            $table->integer('user_id')
                    ->nullable()
                    ->unsigned();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('social_profiles');
    }

}
