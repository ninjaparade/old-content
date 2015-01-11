<?php namespace Ninjaparade\Content\Controllers\Admin;

use DataGrid;
use Input;
use Lang;
use Platform\Access\Controllers\AdminController;
use Redirect;
use Response;
use View;
use Ninjaparade\Content\Repositories\CategorieRepositoryInterface;

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
	 * @var \Ninjaparade\Content\Repositories\CategorieRepositoryInterface
	 */
	protected $categories;

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
	 * @param  \Ninjaparade\Content\Repositories\CategorieRepositoryInterface  $categories
	 * @return void
	 */
	public function __construct(CategorieRepositoryInterface $categories)
	{
		parent::__construct();

		$this->categories = $categories;
	}

	/**
	 * Display a listing of categorie.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return View::make('ninjaparade/content::categories.index');
	}

	/**
	 * Datasource for the categorie Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->categories->grid();

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
	 * Show the form for creating new categorie.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new categorie.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating categorie.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating categorie.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified categorie.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->categories->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("ninjaparade/content::categories/message.{$type}.delete")
		);

		return redirect()->route('admin.ninjaparade.content.categories.all');
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
				$this->categories->{$action}($entry);
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
		// Do we have a categorie identifier?
		if (isset($id))
		{
			if ( ! $categorie = $this->categories->find($id))
			{
				$this->alerts->error(trans('ninjaparade/content::categories/message.not_found', compact('id')));

				return redirect()->route('admin.ninjaparade.content.categories.all');
			}
		}
		else
		{
			$categorie = $this->categories->createModel();
		}

		// Show the page
		return view('ninjaparade/content::categories.form', compact('mode', 'categorie'));
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
		// Store the categorie
		list($messages) = $this->categories->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("ninjaparade/content::categories/message.success.{$mode}"));

			return redirect()->route('admin.ninjaparade.content.categories.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
