<?php

use Illuminate\Foundation\Application;
use Cartalyst\Extensions\ExtensionInterface;
use Cartalyst\Settings\Repository as Settings;
use Cartalyst\Permissions\Container as Permissions;
use URL;

return [

	/*
	|--------------------------------------------------------------------------
	| Name
	|--------------------------------------------------------------------------
	|
	| This is your extension name and it is only required for
	| presentational purposes.
	|
	*/

	'name' => 'Content',

	/*
	|--------------------------------------------------------------------------
	| Slug
	|--------------------------------------------------------------------------
	|
	| This is your extension unique identifier and should not be changed as
	| it will be recognized as a new extension.
	|
	| Ideally, this should match the folder structure within the extensions
	| folder, but this is completely optional.
	|
	*/

	'slug' => 'ninjaparade/content',

	/*
	|--------------------------------------------------------------------------
	| Author
	|--------------------------------------------------------------------------
	|
	| Because everybody deserves credit for their work, right?
	|
	*/

	'author' => 'Ninjaparade',

	/*
	|--------------------------------------------------------------------------
	| Description
	|--------------------------------------------------------------------------
	|
	| One or two sentences describing the extension for users to view when
	| they are installing the extension.
	|
	*/

	'description' => 'content creation for website',

	/*
	|--------------------------------------------------------------------------
	| Version
	|--------------------------------------------------------------------------
	|
	| Version should be a string that can be used with version_compare().
	| This is how the extensions versions are compared.
	|
	*/

	'version' => '0.1.0',

	/*
	|--------------------------------------------------------------------------
	| Requirements
	|--------------------------------------------------------------------------
	|
	| List here all the extensions that this extension requires to work.
	| This is used in conjunction with composer, so you should put the
	| same extension dependencies on your main composer.json require
	| key, so that they get resolved using composer, however you
	| can use without composer, at which point you'll have to
	| ensure that the required extensions are available.
	|
	*/

	'require' => [
		
	],

	/*
	|--------------------------------------------------------------------------
	| Autoload Logic
	|--------------------------------------------------------------------------
	|
	| You can define here your extension autoloading logic, it may either
	| be 'composer', 'platform' or a 'Closure'.
	|
	| If composer is defined, your composer.json file specifies the autoloading
	| logic.
	|
	| If platform is defined, your extension receives convetion autoloading
	| based on the Platform standards.
	|
	| If a Closure is defined, it should take two parameters as defined
	| bellow:
	|
	|	object \Composer\Autoload\ClassLoader      $loader
	|	object \Illuminate\Foundation\Application  $app
	|
	| Supported: "composer", "platform", "Closure"
	|
	*/

	'autoload' => 'composer',

	/*
	|--------------------------------------------------------------------------
	| Service Providers
	|--------------------------------------------------------------------------
	|
	| Define your extension service providers here. They will be dynamically
	| registered without having to include them in app/config/app.php.
	|
	*/

	'providers' => [

		'Ninjaparade\Content\Providers\PostServiceProvider',
		'Ninjaparade\Content\Providers\AuthorServiceProvider',
		'Ninjaparade\Content\Providers\CategoriesServiceProvider',
		// 'Ninjaparade\Content\Providers\CategorieServiceProvider',
		'Ninjaparade\Content\Providers\PosttypeServiceProvider',

	],

	/*
	|--------------------------------------------------------------------------
	| Routes
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| any custom routing logic here.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'routes' => function(ExtensionInterface $extension, Application $app)
	{	
		
		Route::group([
				'prefix'    => admin_uri().'/content/posts',
				'namespace' => 'Ninjaparade\Content\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.ninjaparade.content.posts.all', 'uses' => 'PostsController@index']);
				Route::post('/', ['as' => 'admin.ninjaparade.content.posts.all', 'uses' => 'PostsController@executeAction']);

				Route::get('grid', ['as' => 'admin.ninjaparade.content.posts.grid', 'uses' => 'PostsController@grid']);

				Route::get('create' , ['as' => 'admin.ninjaparade.content.posts.create', 'uses' => 'PostsController@create']);
				Route::post('create', ['as' => 'admin.ninjaparade.content.posts.create', 'uses' => 'PostsController@store']);

				Route::get('{id}'   , ['as' => 'admin.ninjaparade.content.posts.edit'  , 'uses' => 'PostsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.ninjaparade.content.posts.edit'  , 'uses' => 'PostsController@update']);

				Route::delete('{id}', ['as' => 'admin.ninjaparade.content.posts.delete', 'uses' => 'PostsController@delete']);
			});

		Route::group([
			'prefix'    => 'content/posts',
			'namespace' => 'Ninjaparade\Content\Controllers\Frontend',
		], function()
		{
			Route::get('/', 'PostsController@index');
		});

					Route::group([
				'prefix'    => admin_uri().'/content/authors',
				'namespace' => 'Ninjaparade\Content\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.ninjaparade.content.authors.all', 'uses' => 'AuthorsController@index']);
				Route::post('/', ['as' => 'admin.ninjaparade.content.authors.all', 'uses' => 'AuthorsController@executeAction']);

				Route::get('grid', ['as' => 'admin.ninjaparade.content.authors.grid', 'uses' => 'AuthorsController@grid']);

				Route::get('create' , ['as' => 'admin.ninjaparade.content.authors.create', 'uses' => 'AuthorsController@create']);
				Route::post('create', ['as' => 'admin.ninjaparade.content.authors.create', 'uses' => 'AuthorsController@store']);

				Route::get('{id}'   , ['as' => 'admin.ninjaparade.content.authors.edit'  , 'uses' => 'AuthorsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.ninjaparade.content.authors.edit'  , 'uses' => 'AuthorsController@update']);

				Route::delete('{id}', ['as' => 'admin.ninjaparade.content.authors.delete', 'uses' => 'AuthorsController@delete']);
			});

		Route::group([
			'prefix'    => 'content/authors',
			'namespace' => 'Ninjaparade\Content\Controllers\Frontend',
		], function()
		{
			Route::get('/', 'AuthorsController@index');
		});

					Route::group([
				'prefix'    => admin_uri().'/content/categories',
				'namespace' => 'Ninjaparade\Content\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.ninjaparade.content.categories.all', 'uses' => 'CategoriesController@index']);
				Route::post('/', ['as' => 'admin.ninjaparade.content.categories.all', 'uses' => 'CategoriesController@executeAction']);

				Route::get('grid', ['as' => 'admin.ninjaparade.content.categories.grid', 'uses' => 'CategoriesController@grid']);

				Route::get('create' , ['as' => 'admin.ninjaparade.content.categories.create', 'uses' => 'CategoriesController@create']);
				Route::post('create', ['as' => 'admin.ninjaparade.content.categories.create', 'uses' => 'CategoriesController@store']);

				Route::get('{id}'   , ['as' => 'admin.ninjaparade.content.categories.edit'  , 'uses' => 'CategoriesController@edit']);
				Route::post('{id}'  , ['as' => 'admin.ninjaparade.content.categories.edit'  , 'uses' => 'CategoriesController@update']);

				Route::delete('{id}', ['as' => 'admin.ninjaparade.content.categories.delete', 'uses' => 'CategoriesController@delete']);
			});

		Route::group([
			'prefix'    => 'content/categories',
			'namespace' => 'Ninjaparade\Content\Controllers\Frontend',
		], function()
		{
			Route::get('/', 'CategoriesController@index');
		});

					Route::group([
				'prefix'    => admin_uri().'/content/posttypes',
				'namespace' => 'Ninjaparade\Content\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.ninjaparade.content.posttypes.all', 'uses' => 'PosttypesController@index']);
				Route::post('/', ['as' => 'admin.ninjaparade.content.posttypes.all', 'uses' => 'PosttypesController@executeAction']);

				Route::get('grid', ['as' => 'admin.ninjaparade.content.posttypes.grid', 'uses' => 'PosttypesController@grid']);

				Route::get('create' , ['as' => 'admin.ninjaparade.content.posttypes.create', 'uses' => 'PosttypesController@create']);
				Route::post('create', ['as' => 'admin.ninjaparade.content.posttypes.create', 'uses' => 'PosttypesController@store']);

				Route::get('{id}'   , ['as' => 'admin.ninjaparade.content.posttypes.edit'  , 'uses' => 'PosttypesController@edit']);
				Route::post('{id}'  , ['as' => 'admin.ninjaparade.content.posttypes.edit'  , 'uses' => 'PosttypesController@update']);

				Route::delete('{id}', ['as' => 'admin.ninjaparade.content.posttypes.delete', 'uses' => 'PosttypesController@delete']);
			});

		Route::group([
			'prefix'    => 'content/posttypes',
			'namespace' => 'Ninjaparade\Content\Controllers\Frontend',
		], function()
		{
			Route::get('/', 'PosttypesController@index');
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Database Seeds
	|--------------------------------------------------------------------------
	|
	| Platform provides a very simple way to seed your database with test
	| data using seed classes. All seed classes should be stored on the
	| `database/seeds` directory within your extension folder.
	|
	| The order you register your seed classes on the array below
	| matters, as they will be ran in the exact same order.
	|
	| The seeds array should follow the following structure:
	|
	|	Vendor\Namespace\Database\Seeds\FooSeeder
	|	Vendor\Namespace\Database\Seeds\BarSeeder
	|
	*/

	'seeds' => [

	],

	/*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
	| Register here all the permissions that this extension has. These will
	| be shown in the user management area to build a graphical interface
	| where permissions can be selected to allow or deny user access.
	|
	| For detailed instructions on how to register the permissions, please
	| refer to the following url https://cartalyst.com/manual/permissions
	|
	*/

	'permissions' => function(Permissions $permissions)
	{
		$permissions->group('post', function($g)
		{
			$g->name = 'Posts';

			$g->permission('post.index', function($p)
			{
				$p->label = trans('ninjaparade/content::posts/permissions.index');

				$p->controller('Ninjaparade\Content\Controllers\Admin\PostsController', 'index, grid');
			});

			$g->permission('post.create', function($p)
			{
				$p->label = trans('ninjaparade/content::posts/permissions.create');

				$p->controller('Ninjaparade\Content\Controllers\Admin\PostsController', 'create, store');
			});

			$g->permission('post.edit', function($p)
			{
				$p->label = trans('ninjaparade/content::posts/permissions.edit');

				$p->controller('Ninjaparade\Content\Controllers\Admin\PostsController', 'edit, update');
			});

			$g->permission('post.delete', function($p)
			{
				$p->label = trans('ninjaparade/content::posts/permissions.delete');

				$p->controller('Ninjaparade\Content\Controllers\Admin\PostsController', 'delete');
			});
		});

		$permissions->group('author', function($g)
		{
			$g->name = 'Authors';

			$g->permission('author.index', function($p)
			{
				$p->label = trans('ninjaparade/content::authors/permissions.index');

				$p->controller('Ninjaparade\Content\Controllers\Admin\AuthorsController', 'index, grid');
			});

			$g->permission('author.create', function($p)
			{
				$p->label = trans('ninjaparade/content::authors/permissions.create');

				$p->controller('Ninjaparade\Content\Controllers\Admin\AuthorsController', 'create, store');
			});

			$g->permission('author.edit', function($p)
			{
				$p->label = trans('ninjaparade/content::authors/permissions.edit');

				$p->controller('Ninjaparade\Content\Controllers\Admin\AuthorsController', 'edit, update');
			});

			$g->permission('author.delete', function($p)
			{
				$p->label = trans('ninjaparade/content::authors/permissions.delete');

				$p->controller('Ninjaparade\Content\Controllers\Admin\AuthorsController', 'delete');
			});
		});

		$permissions->group('categories', function($g)
		{
			$g->name = 'Categories';

			$g->permission('categories.index', function($p)
			{
				$p->label = trans('ninjaparade/content::categories/permissions.index');

				$p->controller('Ninjaparade\Content\Controllers\Admin\CategoriesController', 'index, grid');
			});

			$g->permission('categories.create', function($p)
			{
				$p->label = trans('ninjaparade/content::categories/permissions.create');

				$p->controller('Ninjaparade\Content\Controllers\Admin\CategoriesController', 'create, store');
			});

			$g->permission('categories.edit', function($p)
			{
				$p->label = trans('ninjaparade/content::categories/permissions.edit');

				$p->controller('Ninjaparade\Content\Controllers\Admin\CategoriesController', 'edit, update');
			});

			$g->permission('categories.delete', function($p)
			{
				$p->label = trans('ninjaparade/content::categories/permissions.delete');

				$p->controller('Ninjaparade\Content\Controllers\Admin\CategoriesController', 'delete');
			});
		});

		$permissions->group('categorie', function($g)
		{
			$g->name = 'Categories';

			$g->permission('categorie.index', function($p)
			{
				$p->label = trans('ninjaparade/content::categories/permissions.index');

				$p->controller('Ninjaparade\Content\Controllers\Admin\CategoriesController', 'index, grid');
			});

			$g->permission('categorie.create', function($p)
			{
				$p->label = trans('ninjaparade/content::categories/permissions.create');

				$p->controller('Ninjaparade\Content\Controllers\Admin\CategoriesController', 'create, store');
			});

			$g->permission('categorie.edit', function($p)
			{
				$p->label = trans('ninjaparade/content::categories/permissions.edit');

				$p->controller('Ninjaparade\Content\Controllers\Admin\CategoriesController', 'edit, update');
			});

			$g->permission('categorie.delete', function($p)
			{
				$p->label = trans('ninjaparade/content::categories/permissions.delete');

				$p->controller('Ninjaparade\Content\Controllers\Admin\CategoriesController', 'delete');
			});
		});

		$permissions->group('posttype', function($g)
		{
			$g->name = 'Posttypes';

			$g->permission('posttype.index', function($p)
			{
				$p->label = trans('ninjaparade/content::posttypes/permissions.index');

				$p->controller('Ninjaparade\Content\Controllers\Admin\PosttypesController', 'index, grid');
			});

			$g->permission('posttype.create', function($p)
			{
				$p->label = trans('ninjaparade/content::posttypes/permissions.create');

				$p->controller('Ninjaparade\Content\Controllers\Admin\PosttypesController', 'create, store');
			});

			$g->permission('posttype.edit', function($p)
			{
				$p->label = trans('ninjaparade/content::posttypes/permissions.edit');

				$p->controller('Ninjaparade\Content\Controllers\Admin\PosttypesController', 'edit, update');
			});

			$g->permission('posttype.delete', function($p)
			{
				$p->label = trans('ninjaparade/content::posttypes/permissions.delete');

				$p->controller('Ninjaparade\Content\Controllers\Admin\PosttypesController', 'delete');
			});
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Widgets
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| all your custom widgets here. Of course, Platform will guess the
	| widget class for you, this is just for custom widgets or if you
	| do not wish to make a new class for a very small widget.
	|
	*/

	'widgets' => function()
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Settings
	|--------------------------------------------------------------------------
	|
	| Register any settings for your extension. You can also configure
	| the namespace and group that a setting belongs to.
	|
	*/

	'settings' => function()
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Menus
	|--------------------------------------------------------------------------
	|
	| You may specify the default various menu hierarchy for your extension.
	| You can provide a recursive array of menu children and their children.
	| These will be created upon installation, synchronized upon upgrading
	| and removed upon uninstallation.
	|
	| Menu children are automatically put at the end of the menu for extensions
	| installed through the Operations extension.
	|
	| The default order (for extensions installed initially) can be
	| found by editing app/config/platform.php.
	|
	*/

	'menus' => [

		'admin' => [
			[
				'slug' => 'admin-ninjaparade-content',
				'name' => 'Content',
				'class' => 'fa fa-circle-o',
				'uri' => 'content',
				'children' => [
					[
						'slug'  => 'admin-ninjaparade-content-post',
						'name'  => 'Posts',
						'class' => 'fa fa-circle-o',
						'uri'   => 'content/posts',
					],
					[
						'slug'  => 'admin-ninjaparade-content-author',
						'name'  => 'Authors',
						'class' => 'fa fa-circle-o',
						'uri'   => 'content/authors',
					],
					[
						'slug'  => 'admin-ninjaparade-content-categories',
						'name'  => 'Categories',
						'class' => 'fa fa-circle-o',
						'uri'   => 'content/categories',
					],
					[
						'slug'  => 'admin-ninjaparade-content-categorie',
						'name'  => 'Categories',
						'class' => 'fa fa-circle-o',
						'uri'   => 'content/categories',
					],
					[
						'slug'  => 'admin-ninjaparade-content-posttype',
						'name'  => 'Posttypes',
						'class' => 'fa fa-circle-o',
						'uri'   => 'content/posttypes',
					],
				],
			],
		],
		'main' => [
			
		],
	],

];
