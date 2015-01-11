<?php namespace Ninjaparade\Content\Controllers\Admin;

use DataGrid;
use Input;
use Lang;
use Platform\Access\Controllers\AdminController;
use Redirect;
use Response;
use View;
use Ninjaparade\Content\Repositories\AuthorRepositoryInterface;

class AuthorsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Content repository.
	 *
	 * @var \Ninjaparade\Content\Repositories\AuthorRepositoryInterface
	 */
	protected $authors;

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
	 * @param  \Ninjaparade\Content\Repositories\AuthorRepositoryInterface  $authors
	 * @return void
	 */
	public function __construct(AuthorRepositoryInterface $authors)
	{
		parent::__construct();

		$this->authors = $authors;
	}

	/**
	 * Display a listing of author.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return View::make('ninjaparade/content::authors.index');
	}

	/**
	 * Datasource for the author Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->authors->grid();

		$columns = [
			'id',
			'name',
			'postion',
			'bio',
			'avatar',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		return DataGrid::make($data, $columns, $settings);
	}

	/**
	 * Show the form for creating new author.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new author.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating author.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating author.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified author.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->authors->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("ninjaparade/content::authors/message.{$type}.delete")
		);

		return redirect()->route('admin.ninjaparade.content.authors.all');
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
				$this->authors->{$action}($entry);
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
		// Do we have a author identifier?
		if (isset($id))
		{
			if ( ! $author = $this->authors->find($id))
			{
				$this->alerts->error(trans('ninjaparade/content::authors/message.not_found', compact('id')));

				return redirect()->route('admin.ninjaparade.content.authors.all');
			}
		}
		else
		{
			$author = $this->authors->createModel();
		}

		// Show the page
		return view('ninjaparade/content::authors.form', compact('mode', 'author'));
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
		// Store the author
		list($messages) = $this->authors->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("ninjaparade/content::authors/message.success.{$mode}"));

			return redirect()->route('admin.ninjaparade.content.authors.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
