<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKvedTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('kveds', function(Blueprint $table) {
        $table->engine = 'InnoDB';
        $table->increments('id');
        $table->string('kved', 5)->unique();
        $table->text('description')->default(null);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
       Schema::drop('kveds');
    }

}
