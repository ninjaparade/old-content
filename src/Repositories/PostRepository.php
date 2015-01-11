<?php namespace Ninjaparade\Content\Repositories;

use Validator;
use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;
use Ninjaparade\Content\Models\Post;

class PostRepository implements PostRepositoryInterface {

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

		$this->data = $app['ninjaparade.content.post.handler.data'];

		$this->setValidator($app['ninjaparade.content.post.validator']);

		$this->setModel(get_class($app['Ninjaparade\Content\Models\Post']));
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
		return $this->container['cache']->rememberForever('ninjaparade.content.post.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('ninjaparade.content.post.'.$id, function() use ($id)
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
		// Create a new post
		$post = $this->createModel();

		// Fire the 'ninjaparade.content.post.creating' event
		if ($this->fireEvent('ninjaparade.content.post.creating', [ $input ]) === false)
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
			// Save the post
			$post->fill($data)->save();

			// Fire the 'ninjaparade.content.post.created' event
			$this->fireEvent('ninjaparade.content.post.created', [ $post ]);
		}

		return [ $messages, $post ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the post object
		$post = $this->find($id);

		// Fire the 'ninjaparade.content.postupdating' event
		if ($this->fireEvent('ninjaparade.content.postupdating', [ $post, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($post, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the post
			$post->fill($data)->save();

			// Fire the 'ninjaparade.content.postupdated' event
			$this->fireEvent('ninjaparade.content.postupdated', [ $post ]);
		}

		return [ $messages, $post ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the post exists
		if ($post = $this->find($id))
		{
			// Fire the 'ninjaparade.content.post.deleted' event
			$this->fireEvent('ninjaparade.content.post.deleted', [ $post ]);

			// Delete the post entry
			$post->delete();

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
