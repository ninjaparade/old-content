<?php namespace Ninjaparade\Content\Repositories;

use Validator;
use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;
use Ninjaparade\Content\Models\Categories;

class CategoriesRepository implements CategoriesRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Ninjaparade\Content\Handlers\DataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent content model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['ninjaparade.content.categories.handler.data'];

		$this->setValidator($app['ninjaparade.content.categories.validator']);

		$this->setModel(get_class($app['Ninjaparade\Content\Models\Categories']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('ninjaparade.content.categories.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('ninjaparade.content.categories.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new categories
		$categories = $this->createModel();

		// Fire the 'ninjaparade.content.categories.creating' event
		if ($this->fireEvent('ninjaparade.content.categories.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the categories
			$categories->fill($data)->save();

			// Fire the 'ninjaparade.content.categories.created' event
			$this->fireEvent('ninjaparade.content.categories.created', [ $categories ]);
		}

		return [ $messages, $categories ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the categories object
		$categories = $this->find($id);

		// Fire the 'ninjaparade.content.categoriesupdating' event
		if ($this->fireEvent('ninjaparade.content.categoriesupdating', [ $categories, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($categories, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the categories
			$categories->fill($data)->save();

			// Fire the 'ninjaparade.content.categoriesupdated' event
			$this->fireEvent('ninjaparade.content.categoriesupdated', [ $categories ]);
		}

		return [ $messages, $categories ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the categories exists
		if ($categories = $this->find($id))
		{
			// Fire the 'ninjaparade.content.categories.deleted' event
			$this->fireEvent('ninjaparade.content.categories.deleted', [ $categories ]);

			// Delete the categories entry
			$categories->delete();

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}
