<?php namespace Ninjaparade\Content\Controllers\Frontend;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Ninjaparade\Content\Repositories\PostRepositoryInterface;
use Platform\Foundation\Controllers\BaseController;
use Redirect;
use View;
use Sentinel;

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
        $post = $this->post->bySlug($slug);
        
		if( ! $post )
		{
            throw new HttpException(404, 'Post does not exist.');
		}

		$pass = true;
		
        if( $post->private)
        {
            $pass = false;

            if( $user = Sentinel::check())
            {
                $pass = true;

                $groups = $post->groups;

                $user_group = $user->groups->lists('id');

                if ( ! empty($mediaGroups) and ! array_intersect($groups, $user_group))
                {
                    $pass = false;
                }

            }
        }

        if ( ! $pass)
        {
            throw new HttpException(403, "You don't have permission to access this content.");
        }

        $view = $this->getView($posttype. '-single');

        return View::make($view)->with(compact('post'));
		
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
