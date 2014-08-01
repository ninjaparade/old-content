<?php namespace Ninjaparade\Content\Models;

use Platform\Attributes\Models\Entity;

class Post extends Entity {

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'posts';

	/**
	 * {@inheritDoc}
	 */
	protected $guarded = [
		'id',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $with = [
		'values.attribute',
	];


	 protected $touches = ['post', 'tags', 'author'];
	/**
	 * {@inheritDoc}
	 */
	protected $eavNamespace = 'ninjaparade/content.post';



	public function author()
    {
        return $this->belongsTo('Ninjaparade\Content\Models\Author');
    }


    public function post()
    {
        return $this->belongsTo('Ninjaparade\Content\Models\Post');
    }


    public function tags()
    {
      return $this->belongsToMany('Ninjaparade\Content\Models\Tag');
    }


    public function categories()
    {
      return $this->belongsToMany('Ninjaparade\Content\Models\Category');
    }


}
