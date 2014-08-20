<?php namespace Ninjaparade\Content\Repositories;

use Cartalyst\Interpret\Interpreter;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Ninjaparade\Content\Models\Posttype;
use Symfony\Component\Finder\Finder;
use Validator;

class DbPosttypeRepository implements PosttypeRepositoryInterface {

	/**
	 * The Eloquent content model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * The event dispatcher instance.
	 *
	 * @var \Illuminate\Events\Dispatcher
	 */
	protected $dispatcher;

	/**
	 * Holds the form validation rules.
	 *
	 * @var array
	 */
	protected $rules = [

	];

	/**
	 * Constructor.
	 *
	 * @param  string  $model
	 * @param  \Illuminate\Events\Dispatcher  $dispatcher
	 * @return void
	 */
	public function __construct($model, Dispatcher $dispatcher)
	{
		$this->model = $model;

		$this->dispatcher = $dispatcher;
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
		return $this
			->createModel()
			->newQuery()
			->get();
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this
			->createModel()
			->where('id', (int) $id)
			->first();
	}

	public function byPostType($posttype)
	{
		
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $data)
	{
		return $this->validatePosttype($data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $data)
	{
		return $this->validatePosttype($data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $data)
	{
		with($posttype = $this->createModel())->fill($data)->save();

		$this->dispatcher->fire('ninjaparade.content.posttype.created', $posttype);

		return $posttype;
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $data)
	{
		$posttype = $this->find($id);

		$posttype->fill($data)->save();

		$this->dispatcher->fire('ninjaparade.content.posttype.updated', $posttype);

		return $posttype;
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		if ($posttype = $this->find($id))
		{
			$this->dispatcher->fire('ninjaparade.content.posttype.deleted', $posttype);

			$posttype->delete();

			return true;
		}

		return false;
	}

	/**
	 * Create a new instance of the model.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function createModel(array $data = [])
	{
		$class = '\\'.ltrim($this->model, '\\');

		return new $class($data);
	}

	/**
	 * Validates a content entry.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	protected function validatePosttype($data)
	{
		$validator = Validator::make($data, $this->rules);

		$validator->passes();

		return $validator->errors();
	}

}
