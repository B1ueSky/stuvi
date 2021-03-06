<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBooksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('books', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('title');
            $table->integer('edition')->nullable()->default(1);
            $table->string('isbn10', 10);
            $table->string('isbn13', 13);
            $table->smallInteger('num_pages')->nullable();
            $table->boolean('verified')->default(false);
            $table->string('language')->nullable();
            $table->integer('list_price')->nullable();
			$table->integer('lowest_price')->nullable();
			$table->integer('highest_price')->nullable();
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
		Schema::drop('books');
	}

}
