<?php namespace Ninjaparade\Content\Models;

use Platform\Attributes\Models\Entity;

class Category extends Entity {

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'categories';

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

	/**
	 * {@inheritDoc}
	 */
	protected $eavNamespace = 'ninjaparade/content.category';


	public function categories()
    {
        return $this->belongsToMany('Ninjaparade\Content\Models\Post');
    }

}
