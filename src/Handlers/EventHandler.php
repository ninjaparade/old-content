<?php namespace Ninjaparade\Content\Handlers;

use Illuminate\Events\Dispatcher;
use Ninjaparade\Content\Models\Posttype;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class EventHandler extends BaseEventHandler implements EventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('ninjaparade.content.posttype.creating', __CLASS__.'@creating');
		$dispatcher->listen('ninjaparade.content.posttype.created', __CLASS__.'@created');

		$dispatcher->listen('ninjaparade.content.posttype.updating', __CLASS__.'@updating');
		$dispatcher->listen('ninjaparade.content.posttype.updated', __CLASS__.'@updated');

		$dispatcher->listen('ninjaparade.content.posttype.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Posttype $posttype)
	{
		$this->flushCache($posttype);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Posttype $posttype, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Posttype $posttype)
	{
		$this->flushCache($posttype);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Posttype $posttype)
	{
		$this->flushCache($posttype);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Ninjaparade\Content\Models\Posttype  $posttype
	 * @return void
	 */
	protected function flushCache(Posttype $posttype)
	{
		$this->app['cache']->forget('ninjaparade.content.posttype.all');

		$this->app['cache']->forget('ninjaparade.content.posttype.'.$posttype->id);
	}

}
