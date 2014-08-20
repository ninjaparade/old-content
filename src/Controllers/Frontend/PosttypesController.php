<?php namespace Ninjaparade\Content\Controllers\Frontend;

use Platform\Foundation\Controllers\BaseController;
use View;

use Ninjaparade\Content\Repositories\PosttypeRepositoryInterface;

class PosttypesController extends BaseController {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */

	public function __construct( PosttypeRepositoryInterface $posttype)
	{
		parent::__construct();

		// $this->posttype = $posttype;
	}

	public function posts($posttype)
	{
		
	}


}
