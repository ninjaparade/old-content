<?php namespace Ninjaparade\Content\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use View;

class PostsController extends Controller {

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
