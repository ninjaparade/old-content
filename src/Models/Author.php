<?php namespace Ninjaparade\Content\Models;

use Platform\Attributes\Models\Entity;

class Author extends Entity {

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'authors';

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
	protected $eavNamespace = 'ninjaparade/content.author';

}
