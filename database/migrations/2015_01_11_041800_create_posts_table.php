<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function(Blueprint $table)
		{
			// $table->increments('id');
			// $table->integer('author_id')->unsigned();
			// $table->string('post_type')->nullable();
			// $table->string('slug')->unsigned()->unique();
			// $table->string('excerpt')->nullable();
			// $table->string('title');
			// $table->longText('body');
			// $table->string('cover_image')->nullable();
			// $table->string('images')->nullable();
			// $table->tinyInteger('publish_status')->default('0')->unsigned();
			// $table->tinyInteger('private')->default('0')->unsigned();
			// $table->timestamps();

			$table->increments('id', true)->unsigned();
			$table->unsignedInteger('author_id');
			$table->string('post_type')->nullable();
			$table->string('slug')->unique();
			$table->string('excerpt')->nullable();
			$table->string('title');
			$table->longText('body');
			$table->string('cover_image')->nullable();
			$table->string('images')->nullable();
			$table->tinyInteger('publish_status')->default('0')->unsigned();
			$table->tinyInteger('private')->default('0')->unsigned();

			$table->timestamps();
			
			$table->index('slug');
			$table->index('author_id');
			
			$table->engine = 'InnoDB';
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts');
	}

}
