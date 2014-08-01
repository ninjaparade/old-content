<?php namespace Ninjaparade\Content\Controllers\Admin;

use DataGrid;
use Input;
use Lang;
use Platform\Admin\Controllers\Admin\AdminController;
use Redirect;
use Response;
use View;
use Ninjaparade\Content\Repositories\PostRepositoryInterface;
use Ninjaparade\Content\Repositories\AuthorRepositoryInterface;
use Ninjaparade\Content\Repositories\PosttypeRepositoryInterface;

class PostsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Content repository.
	 *
	 * @var \Ninjaparade\Content\Repositories\PostRepositoryInterface
	 */
	protected $post;

	/**
	 * The Content repository.
	 *
	 * @var \Ninjaparade\Content\Repositories\AuthorRepositoryInterface
	 */
	protected $author;

	/**
	 * The Content repository.
	 *
	 * @var \Ninjaparade\Content\Repositories\PosttypeRepositoryInterface
	 */
	protected $posttype;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Ninjaparade\Content\Repositories\PostRepositoryInterface  $post
	 * @return void
	 */
	public function __construct(PostRepositoryInterface $post, AuthorRepositoryInterface $author, PosttypeRepositoryInterface $posttype)
	{
		parent::__construct();

		$this->post     = $post;
		$this->author   = $author;
		$this->posttype = $posttype;
	}

	/**
	 * Display a listing of post.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return View::make('ninjaparade/content::posts.index');
	}

	/**
	 * Datasource for the post Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->post->grid();

		$columns = [
			'id',
			'author_id',
			'post_type',
			'slug',
			'pullquote',
			'title',
			'content',
			'publish_status',
			'private',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		return DataGrid::make($data, $columns, $settings);
	}

	/**
	 * Show the form for creating new post.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new post.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating post.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating post.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified post.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		if ($this->post->delete($id))
		{
			$message = Lang::get('ninjaparade/content::posts/message.success.delete');

			return Redirect::toAdmin('content/posts')->withSuccess($message);
		}

		$message = Lang::get('ninjaparade/content::posts/message.error.delete');

		return Redirect::toAdmin('content/posts')->withErrors($message);
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = Input::get('action');

		if (in_array($action, $this->actions))
		{
			foreach (Input::get('entries', []) as $entry)
			{
				$this->post->{$action}($entry);
			}

			return Response::json('Success');
		}

		return Response::json('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a post identifier?
		if (isset($id))
		{
			if ( ! $post = $this->post->find($id))
			{
				$message = Lang::get('ninjaparade/content::posts/message.not_found', compact('id'));

				return Redirect::toAdmin('content/posts')->withErrors($message);
			}
		}
		else
		{
			$post = $this->post->createModel();
		}

		$authors = $this->author->findAll();
		$posttypes = $this->posttype->findAll();

		// Show the page
		return View::make('ninjaparade/content::posts.form', compact('mode', 'post', 'authors', 'posttypes'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Get the input data
		$data = Input::all();

		// Do we have a post identifier?
		if ($id)
		{
			// Check if the data is valid
			$messages = $this->post->validForUpdate($id, $data);

			// Do we have any errors?
			if ($messages->isEmpty())
			{
				// Update the post
				$post = $this->post->update($id, $data);
			}
		}
		else
		{
			// Check if the data is valid
			$messages = $this->post->validForCreation($data);

			// Do we have any errors?
			if ($messages->isEmpty())
			{
				// Create the post
				$post = $this->post->create($data);
			}
		}

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			// Prepare the success message
			$message = Lang::get("ninjaparade/content::posts/message.success.{$mode}");

			return Redirect::toAdmin("content/posts/{$post->id}/edit")->withSuccess($message);
		}

		return Redirect::back()->withInput()->withErrors($messages);
	}

}
