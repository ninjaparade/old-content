<?php namespace Ninjaparade\Content\Providers;

use Cartalyst\Support\ServiceProvider;

class PosttypeServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		$this->package('ninjaparade/content', 'ninjaparade/content', __DIR__.'/..');

		// Register the extension component namespaces
		$this->package('ninjaparade/content', 'ninjaparade/content', __DIR__.'/../..');

		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Ninjaparade\Content\Models\Posttype']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('ninjaparade.content.posttype.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('ninjaparade.content.posttype', 'Ninjaparade\Content\Repositories\PosttypeRepository');

		// Register the data handler
		$this->bindIf('ninjaparade.content.posttype.handler.data', 'Ninjaparade\Content\Handlers\DataHandler');

		// Register the event handler
		$this->bindIf('ninjaparade.content.posttype.handler.event', 'Ninjaparade\Content\Handlers\EventHandler');

		// Register the validator
		$this->bindIf('ninjaparade.content.posttype.validator', 'Ninjaparade\Content\Validator\PosttypeValidator');
	}

}
