<?php namespace Ninjaparade\Content\Controllers\Admin;

use DataGrid;
use Input;
use Lang;
use Platform\Access\Controllers\AdminController;
use Redirect;
use Response;
use View;
use Ninjaparade\Content\Repositories\PosttypeRepositoryInterface;

class PosttypesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Content repository.
	 *
	 * @var \Ninjaparade\Content\Repositories\PosttypeRepositoryInterface
	 */
	protected $posttypes;

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
	 * @param  \Ninjaparade\Content\Repositories\PosttypeRepositoryInterface  $posttypes
	 * @return void
	 */
	public function __construct(PosttypeRepositoryInterface $posttypes)
	{
		parent::__construct();

		$this->posttypes = $posttypes;
	}

	/**
	 * Display a listing of posttype.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return View::make('ninjaparade/content::posttypes.index');
	}

	/**
	 * Datasource for the posttype Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->posttypes->grid();

		$columns = [
			'id',
			'slug',
			'name',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		return DataGrid::make($data, $columns, $settings);
	}

	/**
	 * Show the form for creating new posttype.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new posttype.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating posttype.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating posttype.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified posttype.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->posttypes->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("ninjaparade/content::posttypes/message.{$type}.delete")
		);

		return redirect()->route('admin.ninjaparade.content.posttypes.all');
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
				$this->posttypes->{$action}($entry);
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
		// Do we have a posttype identifier?
		if (isset($id))
		{
			if ( ! $posttype = $this->posttypes->find($id))
			{
				$this->alerts->error(trans('ninjaparade/content::posttypes/message.not_found', compact('id')));

				return redirect()->route('admin.ninjaparade.content.posttypes.all');
			}
		}
		else
		{
			$posttype = $this->posttypes->createModel();
		}

		// Show the page
		return view('ninjaparade/content::posttypes.form', compact('mode', 'posttype'));
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
		// Store the posttype
		list($messages) = $this->posttypes->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("ninjaparade/content::posttypes/message.success.{$mode}"));

			return redirect()->route('admin.ninjaparade.content.posttypes.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
