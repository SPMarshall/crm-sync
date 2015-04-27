<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDbSchema extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('users', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('email',100)->unique();
            $table->string('fio',100);
            $table->bigInteger('inn')->unique();
            $table->string('company_name',150);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('kveds', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('kved',5)->unique();
            $table->text('description')->default(null);
            $table->boolean('edited')->default(false);
            $table->enum('operation', array('update', 'delete'))->default('update'); 	
            $table->index(['operation','edited']);
            $table->timestamps();
        });
        
        Schema::create('kved_user', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->integer('kved_id')->unsigned()->index();
            $table->foreign('kved_id')->references('id')->on('kveds')->onDelete('cascade');
            $table->boolean('main')->default(false);
        });
    
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('kved_user');
        Schema::drop('users');
        Schema::drop('kveds');
    }

}
