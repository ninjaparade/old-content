<?php namespace Ninjaparade\Content\Models;

use Platform\Attributes\Models\Entity;

class Posttype extends Entity {

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'posttypes';

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
	protected $eavNamespace = 'ninjaparade/content.posttype';

}
