<?php namespace Ninjaparade\Content\Controllers\Admin;

use DataGrid;
use Input;
use Lang;
use Platform\Access\Controllers\AdminController;
use Redirect;
use Response;
use View;
use Ninjaparade\Content\Repositories\PostRepositoryInterface;

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
	protected $posts;

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
	 * @param  \Ninjaparade\Content\Repositories\PostRepositoryInterface  $posts
	 * @return void
	 */
	public function __construct(PostRepositoryInterface $posts)
	{
		parent::__construct();

		$this->posts = $posts;
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
		$data = $this->posts->grid();

		$columns = [
			'id',
			'author_id',
			'post_type',
			'slug',
			'excerpt',
			'title',
			'body',
			'cover_image',
			'images',
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
		$type = $this->posts->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("ninjaparade/content::posts/message.{$type}.delete")
		);

		return redirect()->route('admin.ninjaparade.content.posts.all');
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
			foreach (Input::get('rows', []) as $entry)
			{
				$this->posts->{$action}($entry);
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
			if ( ! $post = $this->posts->find($id))
			{
				$this->alerts->error(trans('ninjaparade/content::posts/message.not_found', compact('id')));

				return redirect()->route('admin.ninjaparade.content.posts.all');
			}
		}
		else
		{
			$post = $this->posts->createModel();
		}

		// Show the page
		return view('ninjaparade/content::posts.form', compact('mode', 'post'));
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
		// Store the post
		list($messages) = $this->posts->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("ninjaparade/content::posts/message.success.{$mode}"));

			return redirect()->route('admin.ninjaparade.content.posts.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
