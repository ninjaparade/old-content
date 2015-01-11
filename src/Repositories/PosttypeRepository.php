<?php namespace Ninjaparade\Content\Repositories;

use Validator;
use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;
use Ninjaparade\Content\Models\Posttype;

class PosttypeRepository implements PosttypeRepositoryInterface {

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

		$this->data = $app['ninjaparade.content.posttype.handler.data'];

		$this->setValidator($app['ninjaparade.content.posttype.validator']);

		$this->setModel(get_class($app['Ninjaparade\Content\Models\Posttype']));
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
		return $this->container['cache']->rememberForever('ninjaparade.content.posttype.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('ninjaparade.content.posttype.'.$id, function() use ($id)
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
		// Create a new posttype
		$posttype = $this->createModel();

		// Fire the 'ninjaparade.content.posttype.creating' event
		if ($this->fireEvent('ninjaparade.content.posttype.creating', [ $input ]) === false)
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
			// Save the posttype
			$posttype->fill($data)->save();

			// Fire the 'ninjaparade.content.posttype.created' event
			$this->fireEvent('ninjaparade.content.posttype.created', [ $posttype ]);
		}

		return [ $messages, $posttype ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the posttype object
		$posttype = $this->find($id);

		// Fire the 'ninjaparade.content.posttypeupdating' event
		if ($this->fireEvent('ninjaparade.content.posttypeupdating', [ $posttype, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($posttype, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the posttype
			$posttype->fill($data)->save();

			// Fire the 'ninjaparade.content.posttypeupdated' event
			$this->fireEvent('ninjaparade.content.posttypeupdated', [ $posttype ]);
		}

		return [ $messages, $posttype ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the posttype exists
		if ($posttype = $this->find($id))
		{
			// Fire the 'ninjaparade.content.posttype.deleted' event
			$this->fireEvent('ninjaparade.content.posttype.deleted', [ $posttype ]);

			// Delete the posttype entry
			$posttype->delete();

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
