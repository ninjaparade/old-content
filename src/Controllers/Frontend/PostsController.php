<?php namespace Ninjaparade\Content\Controllers\Frontend;

use Platform\Foundation\Controllers\BaseController;
use View;
use Redirect;
use Ninjaparade\Content\Repositories\PostRepositoryInterface;

class PostsController extends BaseController {

	public function __construct( PostRepositoryInterface $post)
	{
		parent::__construct();

		$this->post = $post;
	}

	public function posts($posttype)
	{
		$view = $this->getView($posttype. '-index');

		$posts = $this->post->byPostType($posttype);

		return View::make($view)->with(compact('posts'));
	}

	public function single($posttype, $slug)
	{
		$view = $this->getView($posttype. '-single');

		$post = $this->post->bySlug($slug);

		if( $post )
		{
			return View::make($view)->with(compact('post'));	
		}else{
			return Redirect::to('/')->withErrors("Sorry No Post found");
		}
		
	}
	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return View::make('ninjaparade/content::index');
	}

	protected function getView($view)
	{
		 return "ninjaparade/content::{$view}";
	}

}
