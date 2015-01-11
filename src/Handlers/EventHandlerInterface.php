<?php namespace Ninjaparade\Content\Handlers;

use Ninjaparade\Content\Models\Posttype;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface EventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a posttype is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a posttype is created.
	 *
	 * @param  \Ninjaparade\Content\Models\Posttype  $posttype
	 * @return mixed
	 */
	public function created(Posttype $posttype);

	/**
	 * When a posttype is being updated.
	 *
	 * @param  \Ninjaparade\Content\Models\Posttype  $posttype
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Posttype $posttype, array $data);

	/**
	 * When a posttype is updated.
	 *
	 * @param  \Ninjaparade\Content\Models\Posttype  $posttype
	 * @return mixed
	 */
	public function updated(Posttype $posttype);

	/**
	 * When a posttype is deleted.
	 *
	 * @param  \Ninjaparade\Content\Models\Posttype  $posttype
	 * @return mixed
	 */
	public function deleted(Posttype $posttype);

}
