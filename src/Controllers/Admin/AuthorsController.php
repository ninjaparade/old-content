<?php namespace Ninjaparade\Content\Controllers\Admin;

use DataGrid;
use Input;
use Lang;
use Platform\Admin\Controllers\Admin\AdminController;
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
	protected $author;

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
	 * @param  \Ninjaparade\Content\Repositories\AuthorRepositoryInterface  $author
	 * @return void
	 */
	public function __construct(AuthorRepositoryInterface $author)
	{
		parent::__construct();

		$this->author = $author;
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
		$data = $this->author->grid();

		$columns = [
			'id',
			'name',
			'position',
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
		if ($this->author->delete($id))
		{
			$message = Lang::get('ninjaparade/content::authors/message.success.delete');

			return Redirect::toAdmin('content/authors')->withSuccess($message);
		}

		$message = Lang::get('ninjaparade/content::authors/message.error.delete');

		return Redirect::toAdmin('content/authors')->withErrors($message);
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
				$this->author->{$action}($entry);
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
			if ( ! $author = $this->author->find($id))
			{
				$message = Lang::get('ninjaparade/content::authors/message.not_found', compact('id'));

				return Redirect::toAdmin('content/authors')->withErrors($message);
			}
		}
		else
		{
			$author = $this->author->createModel();
		}

		// Show the page
		return View::make('ninjaparade/content::authors.form', compact('mode', 'author'));
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

		// Do we have a author identifier?
		if ($id)
		{
			// Check if the data is valid
			$messages = $this->author->validForUpdate($id, $data);

			// Do we have any errors?
			if ($messages->isEmpty())
			{
				// Update the author
				$author = $this->author->update($id, $data);
			}
		}
		else
		{
			// Check if the data is valid
			$messages = $this->author->validForCreation($data);

			// Do we have any errors?
			if ($messages->isEmpty())
			{
				// Create the author
				$author = $this->author->create($data);
			}
		}

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			// Prepare the success message
			$message = Lang::get("ninjaparade/content::authors/message.success.{$mode}");

			return Redirect::toAdmin("content/authors/{$author->id}/edit")->withSuccess($message);
		}

		return Redirect::back()->withInput()->withErrors($messages);
	}

}
