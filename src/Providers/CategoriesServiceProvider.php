<?php namespace Ninjaparade\Content\Providers;

use Cartalyst\Support\ServiceProvider;

class CategoriesServiceProvider extends ServiceProvider {

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
			$this->app['Ninjaparade\Content\Models\Categories']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('ninjaparade.content.categories.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('ninjaparade.content.categories', 'Ninjaparade\Content\Repositories\CategoriesRepository');

		// Register the data handler
		$this->bindIf('ninjaparade.content.categories.handler.data', 'Ninjaparade\Content\Handlers\DataHandler');

		// Register the event handler
		$this->bindIf('ninjaparade.content.categories.handler.event', 'Ninjaparade\Content\Handlers\EventHandler');

		// Register the validator
		$this->bindIf('ninjaparade.content.categories.validator', 'Ninjaparade\Content\Validator\CategoriesValidator');
	}

}
