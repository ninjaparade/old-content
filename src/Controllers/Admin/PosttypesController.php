<?php namespace Ninjaparade\Content\Controllers\Admin;

use DataGrid;
use Input;
use Lang;
use Platform\Admin\Controllers\Admin\AdminController;
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
	 * @param  \Ninjaparade\Content\Repositories\PosttypeRepositoryInterface  $posttype
	 * @return void
	 */
	public function __construct(PosttypeRepositoryInterface $posttype)
	{
		parent::__construct();

		$this->posttype = $posttype;
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
		$data = $this->posttype->grid();

		$columns = [
			'id',
			'slug',
			'title',
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
		if ($this->posttype->delete($id))
		{
			$message = Lang::get('ninjaparade/content::posttypes/message.success.delete');

			return Redirect::toAdmin('content/posttypes')->withSuccess($message);
		}

		$message = Lang::get('ninjaparade/content::posttypes/message.error.delete');

		return Redirect::toAdmin('content/posttypes')->withErrors($message);
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
				$this->posttype->{$action}($entry);
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
			if ( ! $posttype = $this->posttype->find($id))
			{
				$message = Lang::get('ninjaparade/content::posttypes/message.not_found', compact('id'));

				return Redirect::toAdmin('content/posttypes')->withErrors($message);
			}
		}
		else
		{
			$posttype = $this->posttype->createModel();
		}

		// Show the page
		return View::make('ninjaparade/content::posttypes.form', compact('mode', 'posttype'));
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

		// Do we have a posttype identifier?
		if ($id)
		{
			// Check if the data is valid
			$messages = $this->posttype->validForUpdate($id, $data);

			// Do we have any errors?
			if ($messages->isEmpty())
			{
				// Update the posttype
				$posttype = $this->posttype->update($id, $data);
			}
		}
		else
		{
			// Check if the data is valid
			$messages = $this->posttype->validForCreation($data);

			// Do we have any errors?
			if ($messages->isEmpty())
			{
				// Create the posttype
				$posttype = $this->posttype->create($data);
			}
		}

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			// Prepare the success message
			$message = Lang::get("ninjaparade/content::posttypes/message.success.{$mode}");

			return Redirect::toAdmin("content/posttypes/{$posttype->id}/edit")->withSuccess($message);
		}

		return Redirect::back()->withInput()->withErrors($messages);
	}

}
