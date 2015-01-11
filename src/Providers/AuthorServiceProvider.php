<?php namespace Ninjaparade\Content\Providers;

use Cartalyst\Support\ServiceProvider;

class AuthorServiceProvider extends ServiceProvider {

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
			$this->app['Ninjaparade\Content\Models\Author']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('ninjaparade.content.author.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('ninjaparade.content.author', 'Ninjaparade\Content\Repositories\AuthorRepository');

		// Register the data handler
		$this->bindIf('ninjaparade.content.author.handler.data', 'Ninjaparade\Content\Handlers\DataHandler');

		// Register the event handler
		$this->bindIf('ninjaparade.content.author.handler.event', 'Ninjaparade\Content\Handlers\EventHandler');

		// Register the validator
		$this->bindIf('ninjaparade.content.author.validator', 'Ninjaparade\Content\Validator\AuthorValidator');
	}

}
