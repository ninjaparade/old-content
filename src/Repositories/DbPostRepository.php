<?php namespace Ninjaparade\Content\Repositories;

use Cartalyst\Interpret\Interpreter;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Ninjaparade\Content\Models\Post;
use Symfony\Component\Finder\Finder;
use Validator;
use Sentinel;


class DbPostRepository implements PostRepositoryInterface {

	/**
	 * The Sentinel User Object.
	 *
	 * @var string
	 */
	protected $user;

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
	 * Holds the relationships to return with post.
	 *
	 * @var array
	 */
	protected $with = [
		'categories', 'author', 'tags'
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

		$this->user = Sentinel::getUser();
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel()
			->with($this->with);
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this
			->createModel()
			->newQuery()
			->with($this->with)
			->get();
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this
			->createModel()
			->newQuery()
			->where('id', (int) $id)
			->with($this->with)
			->first();
	}

	public function byPostType($posttype, $paginate = true, $count = 5)
	{
		if ( $this->user)
		{
			$posts = $this
				->createModel()
				->newQuery()
				->where([ 'post_type' => $posttype, 'publish_status' => 1,])
				->with($this->with)
				->paginate($count);

		}else{
			$posts = $this
				->createModel()
				->newQuery()
				->where([ 'post_type' => $posttype, 'publish_status' => 1, 'private' => 0])
				->with($this->with)
				->paginate($count);
		}

		return $posts;
	}

	public function bySlug($slug)
	{
		$post = $this
			->createModel()
			->newQuery()
			->where('slug', $slug)
			->with($this->with)
			->first();

		if ( ! $this->user && $post->private)
		{	
			return [];

		}else{

			return $post;
		}


		
	}

	public function attachCategory($post, $category)
	{
		 $post->categories()->detach();
		 $post->categories()->attach($category->id);

		 return true;
	}

	public function attachTags($post, $tags)
	{
		 $post->tags()->detach();
		 $post->tags()->attach($tags);

		 return true;
	}


	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $data)
	{
		return $this->validatePost($data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $data)
	{
		return $this->validatePost($data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $data)
	{
		with($post = $this->createModel())->fill($data)->save();

		$this->dispatcher->fire('ninjaparade.content.post.created', $post);

		return $post;
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $data)
	{
		$post = $this->find($id);

		$post->fill($data)->save();

		$this->dispatcher->fire('ninjaparade.content.post.updated', $post);

		return $post;
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		if ($post = $this->find($id))
		{
			$this->dispatcher->fire('ninjaparade.content.post.deleted', $post);

			$post->delete();

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
	protected function validatePost($data)
	{
		$validator = Validator::make($data, $this->rules);

		$validator->passes();

		return $validator->errors();
	}

}
