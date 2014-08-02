<?php

 use Illuminate\Database\Schema\Blueprint;
 use Illuminate\Database\Migrations\Migration;

class CreateAuthorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('authors', function(Blueprint $table)
		{
			$table->increments('id', true)->unsigned();
			$table->text('name');
			$table->text('position')->nullable();
			$table->text('bio')->nullable();
			$table->integer('avatar')->nullable()->default('0')->unsigned();
			$table->timestamps();

			$table->engine = 'InnoDB';
		});


		Schema::table('posts', function(Blueprint $table){

			$table->foreign('author_id')->references('id')->on('authors');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('authors');
	}

}
