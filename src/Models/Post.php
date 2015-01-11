<?php namespace Ninjaparade\Content\Models;

use InvalidArgumentException;
use Cartalyst\Tags\TaggableTrait;
use Cartalyst\Tags\TaggableInterface;

class Post extends Model implements TaggableInterface {

	use TaggableTrait;

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

	/**
	 * {@inheritDoc}
	 */
	protected static $entityNamespace = 'ninjaparade/content.post';

}
