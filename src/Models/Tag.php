<?php namespace Ninjaparade\Content\Models;

use Platform\Attributes\Models\Entity;

class Tag extends Entity {

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'tags';

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
	protected $eavNamespace = 'ninjaparade/content.tag';

}
