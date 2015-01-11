<?php namespace Ninjaparade\Content\Repositories;

use Validator;
use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;
use Ninjaparade\Content\Models\Author;

class AuthorRepository implements AuthorRepositoryInterface {

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

		$this->data = $app['ninjaparade.content.author.handler.data'];

		$this->setValidator($app['ninjaparade.content.author.validator']);

		$this->setModel(get_class($app['Ninjaparade\Content\Models\Author']));
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
		return $this->container['cache']->rememberForever('ninjaparade.content.author.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('ninjaparade.content.author.'.$id, function() use ($id)
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
		// Create a new author
		$author = $this->createModel();

		// Fire the 'ninjaparade.content.author.creating' event
		if ($this->fireEvent('ninjaparade.content.author.creating', [ $input ]) === false)
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
			// Save the author
			$author->fill($data)->save();

			// Fire the 'ninjaparade.content.author.created' event
			$this->fireEvent('ninjaparade.content.author.created', [ $author ]);
		}

		return [ $messages, $author ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the author object
		$author = $this->find($id);

		// Fire the 'ninjaparade.content.authorupdating' event
		if ($this->fireEvent('ninjaparade.content.authorupdating', [ $author, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($author, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the author
			$author->fill($data)->save();

			// Fire the 'ninjaparade.content.authorupdated' event
			$this->fireEvent('ninjaparade.content.authorupdated', [ $author ]);
		}

		return [ $messages, $author ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the author exists
		if ($author = $this->find($id))
		{
			// Fire the 'ninjaparade.content.author.deleted' event
			$this->fireEvent('ninjaparade.content.author.deleted', [ $author ]);

			// Delete the author entry
			$author->delete();

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
