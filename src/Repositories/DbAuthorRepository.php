<?php namespace Ninjaparade\Content\Repositories;

use Cartalyst\Interpret\Interpreter;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Ninjaparade\Content\Models\Author;
use Symfony\Component\Finder\Finder;
use Validator;

class DbAuthorRepository implements AuthorRepositoryInterface {

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

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $data)
	{
		return $this->validateAuthor($data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $data)
	{
		return $this->validateAuthor($data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $data)
	{
		with($author = $this->createModel())->fill($data)->save();

		$this->dispatcher->fire('ninjaparade.content.author.created', $author);

		return $author;
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $data)
	{
		$author = $this->find($id);

		$author->fill($data)->save();

		$this->dispatcher->fire('ninjaparade.content.author.updated', $author);

		return $author;
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		if ($author = $this->find($id))
		{
			$this->dispatcher->fire('ninjaparade.content.author.deleted', $author);

			$author->delete();

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
	protected function validateAuthor($data)
	{
		$validator = Validator::make($data, $this->rules);

		$validator->passes();

		return $validator->errors();
	}

}
