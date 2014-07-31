<?php namespace Ninjaparade\Content\Controllers\Admin;

use DataGrid;
use Input;
use Lang;
use Platform\Admin\Controllers\Admin\AdminController;
use Redirect;
use Response;
use View;
use Ninjaparade\Content\Repositories\CategoryRepositoryInterface;

class CategoriesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Content repository.
	 *
	 * @var \Ninjaparade\Content\Repositories\CategoryRepositoryInterface
	 */
	protected $category;

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
	 * @param  \Ninjaparade\Content\Repositories\CategoryRepositoryInterface  $category
	 * @return void
	 */
	public function __construct(CategoryRepositoryInterface $category)
	{
		parent::__construct();

		$this->category = $category;
	}

	/**
	 * Display a listing of category.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return View::make('ninjaparade/content::categories.index');
	}

	/**
	 * Datasource for the category Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->category->grid();

		$columns = [
			'id',
			'name',
			'slug',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		return DataGrid::make($data, $columns, $settings);
	}

	/**
	 * Show the form for creating new category.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new category.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating category.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating category.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified category.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		if ($this->category->delete($id))
		{
			$message = Lang::get('ninjaparade/content::categories/message.success.delete');

			return Redirect::toAdmin('content/categories')->withSuccess($message);
		}

		$message = Lang::get('ninjaparade/content::categories/message.error.delete');

		return Redirect::toAdmin('content/categories')->withErrors($message);
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
				$this->category->{$action}($entry);
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
		// Do we have a category identifier?
		if (isset($id))
		{
			if ( ! $category = $this->category->find($id))
			{
				$message = Lang::get('ninjaparade/content::categories/message.not_found', compact('id'));

				return Redirect::toAdmin('content/categories')->withErrors($message);
			}
		}
		else
		{
			$category = $this->category->createModel();
		}

		// Show the page
		return View::make('ninjaparade/content::categories.form', compact('mode', 'category'));
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

		// Do we have a category identifier?
		if ($id)
		{
			// Check if the data is valid
			$messages = $this->category->validForUpdate($id, $data);

			// Do we have any errors?
			if ($messages->isEmpty())
			{
				// Update the category
				$category = $this->category->update($id, $data);
			}
		}
		else
		{
			// Check if the data is valid
			$messages = $this->category->validForCreation($data);

			// Do we have any errors?
			if ($messages->isEmpty())
			{
				// Create the category
				$category = $this->category->create($data);
			}
		}

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			// Prepare the success message
			$message = Lang::get("ninjaparade/content::categories/message.success.{$mode}");

			return Redirect::toAdmin("content/categories/{$category->id}/edit")->withSuccess($message);
		}

		return Redirect::back()->withInput()->withErrors($messages);
	}

}
