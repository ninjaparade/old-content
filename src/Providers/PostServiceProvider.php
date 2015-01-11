<?php namespace Ninjaparade\Content\Providers;

use Cartalyst\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider {

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
			$this->app['Ninjaparade\Content\Models\Post']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('ninjaparade.content.post.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('ninjaparade.content.post', 'Ninjaparade\Content\Repositories\PostRepository');

		// Register the data handler
		$this->bindIf('ninjaparade.content.post.handler.data', 'Ninjaparade\Content\Handlers\DataHandler');

		// Register the event handler
		$this->bindIf('ninjaparade.content.post.handler.event', 'Ninjaparade\Content\Handlers\EventHandler');

		// Register the validator
		$this->bindIf('ninjaparade.content.post.validator', 'Ninjaparade\Content\Validator\PostValidator');
	}

}
