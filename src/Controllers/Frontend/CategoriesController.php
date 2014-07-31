<?php namespace Ninjaparade\Content\Controllers\Frontend;

use Platform\Foundation\Controllers\BaseController;
use View;

class CategoriesController extends BaseController {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return View::make('ninjaparade/content::index');
	}

}
