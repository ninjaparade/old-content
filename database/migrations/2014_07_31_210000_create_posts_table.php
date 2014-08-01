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
			$table->increments('id');
			$table->integer('author_id')->unsigned();
			$table->string('post_type')->nullable();
			$table->string('slug')->unique();
			$table->string('pullquote')->nullable();
			$table->string('title');
			$table->longText('content');
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
