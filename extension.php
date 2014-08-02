<?php

use Cartalyst\Extensions\ExtensionInterface;
use Illuminate\Foundation\Application;

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

	'description' => 'content creator for Platform',

	/*
	|--------------------------------------------------------------------------
	| Version
	|--------------------------------------------------------------------------
	|
	| Version should be a string that can be used with version_compare().
	| This is how the extensions versions are compared.
	|
	*/

	'version' => '0.1.1',

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
		'platform/admin',
		'platform/media',
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
	| Register Callback
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is registered. This can do
	| all the needed custom logic upon registering.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'register' => function(ExtensionInterface $extension, Application $app)
	{
		$PostRepository = 'Ninjaparade\Content\Repositories\PostRepositoryInterface';

		if ( ! $app->bound($PostRepository))
		{
			$app->bind($PostRepository, function($app)
			{
				$model = get_class($app['Ninjaparade\Content\Models\Post']);

				return new Ninjaparade\Content\Repositories\DbPostRepository($model, $app['events']);
			});
		}

		$AuthorRepository = 'Ninjaparade\Content\Repositories\AuthorRepositoryInterface';

		if ( ! $app->bound($AuthorRepository))
		{
			$app->bind($AuthorRepository, function($app)
			{
				$model = get_class($app['Ninjaparade\Content\Models\Author']);

				return new Ninjaparade\Content\Repositories\DbAuthorRepository($model, $app['events']);
			});
		}

		$CategoryRepository = 'Ninjaparade\Content\Repositories\CategoryRepositoryInterface';

		if ( ! $app->bound($CategoryRepository))
		{
			$app->bind($CategoryRepository, function($app)
			{
				$model = get_class($app['Ninjaparade\Content\Models\Category']);

				return new Ninjaparade\Content\Repositories\DbCategoryRepository($model, $app['events']);
			});
		}

		$TagRepository = 'Ninjaparade\Content\Repositories\TagRepositoryInterface';

		if ( ! $app->bound($TagRepository))
		{
			$app->bind($TagRepository, function($app)
			{
				$model = get_class($app['Ninjaparade\Content\Models\Tag']);

				return new Ninjaparade\Content\Repositories\DbTagRepository($model, $app['events']);
			});
		}

		$PosttypeRepository = 'Ninjaparade\Content\Repositories\PosttypeRepositoryInterface';

		if ( ! $app->bound($PosttypeRepository))
		{
			$app->bind($PosttypeRepository, function($app)
			{
				$model = get_class($app['Ninjaparade\Content\Models\Posttype']);

				return new Ninjaparade\Content\Repositories\DbPosttypeRepository($model, $app['events']);
			});
		}
	},

	/*
	|--------------------------------------------------------------------------
	| Boot Callback
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is booted. This can do
	| all the needed custom logic upon booting.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'boot' => function(ExtensionInterface $extension, Application $app)
	{
		if (class_exists('Ninjaparade\Content\Models\Post'))
		{
			// Get the model
			$model = $app['Ninjaparade\Content\Models\Post'];

			// Register a new attribute namespace
			$app['Platform\Attributes\Models\Attribute']->registerNamespace($model);
		}

		if (class_exists('Ninjaparade\Content\Models\Author'))
		{
			// Get the model
			$model = $app['Ninjaparade\Content\Models\Author'];

			// Register a new attribute namespace
			$app['Platform\Attributes\Models\Attribute']->registerNamespace($model);
		}

		if (class_exists('Ninjaparade\Content\Models\Category'))
		{
			// Get the model
			$model = $app['Ninjaparade\Content\Models\Category'];

			// Register a new attribute namespace
			$app['Platform\Attributes\Models\Attribute']->registerNamespace($model);
		}

		if (class_exists('Ninjaparade\Content\Models\Tag'))
		{
			// Get the model
			$model = $app['Ninjaparade\Content\Models\Tag'];

			// Register a new attribute namespace
			$app['Platform\Attributes\Models\Attribute']->registerNamespace($model);
		}

		if (class_exists('Ninjaparade\Content\Models\Posttype'))
		{
			// Get the model
			$model = $app['Ninjaparade\Content\Models\Posttype'];

			// Register a new attribute namespace
			$app['Platform\Attributes\Models\Attribute']->registerNamespace($model);
		}
	},

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
		Route::group(['namespace' => 'Ninjaparade\Content\Controllers'], function()
		{
			Route::group(['prefix' => admin_uri().'/content/posts', 'namespace' => 'Admin'], function()
			{
				Route::get('/', ['as' => 'posts.index', 'uses' => 'PostsController@index'] );
				Route::post('/', 'PostsController@executeAction');
				Route::get('grid', 'PostsController@grid');
				Route::get('create', ['as' => 'posts.create', 'uses' => 'PostsController@create']);
				Route::post('create', 'PostsController@store');
				Route::get('{id}/edit', 'PostsController@edit');
				Route::post('{id}/edit', 'PostsController@update');
				Route::get('{id}/delete', 'PostsController@delete');
			});

			Route::group(['prefix' => 'content/posts', 'namespace' => 'Frontend'], function()
			{
				Route::get('/', 'PostsController@index');
			});
		});

		Route::group(['namespace' => 'Ninjaparade\Content\Controllers'], function()
		{
			Route::group(['prefix' => admin_uri().'/content/authors', 'namespace' => 'Admin'], function()
			{
				Route::get('/', ['as' => 'authors.index', 'uses' => 'AuthorsController@index']);
				Route::post('/', 'AuthorsController@executeAction');
				Route::get('grid', 'AuthorsController@grid');
				Route::get('create', 'AuthorsController@create');
				Route::post('create', 'AuthorsController@store');
				Route::get('{id}/edit', 'AuthorsController@edit');
				Route::post('{id}/edit', 'AuthorsController@update');
				Route::get('{id}/delete', 'AuthorsController@delete');
			});

			Route::group(['prefix' => 'content/authors', 'namespace' => 'Frontend'], function()
			{
				Route::get('/', 'AuthorsController@index');
			});
		});

		Route::group(['namespace' => 'Ninjaparade\Content\Controllers'], function()
		{
			Route::group(['prefix' => admin_uri().'/content/categories', 'namespace' => 'Admin'], function()
			{
				Route::get('/', ['as' => 'categories.index', 'uses' =>'CategoriesController@index']);
				Route::post('/', 'CategoriesController@executeAction');
				Route::get('grid', 'CategoriesController@grid');
				Route::get('create', 'CategoriesController@create');
				Route::post('create', 'CategoriesController@store');
				Route::get('{id}/edit', 'CategoriesController@edit');
				Route::post('{id}/edit', 'CategoriesController@update');
				Route::get('{id}/delete', 'CategoriesController@delete');
			});

			Route::group(['prefix' => 'content/categories', 'namespace' => 'Frontend'], function()
			{
				Route::get('/', 'CategoriesController@index');
			});
		});

		Route::group(['namespace' => 'Ninjaparade\Content\Controllers'], function()
		{
			Route::group(['prefix' => admin_uri().'/content/tags', 'namespace' => 'Admin'], function()
			{
				Route::get('/', ['as' => 'tags.index', 'uses' =>'TagsController@index']);
				Route::post('/', 'TagsController@executeAction');
				Route::get('grid', 'TagsController@grid');
				Route::get('create', 'TagsController@create');
				Route::post('create', 'TagsController@store');
				Route::get('{id}/edit', 'TagsController@edit');
				Route::post('{id}/edit', 'TagsController@update');
				Route::get('{id}/delete', 'TagsController@delete');
			});

			Route::group(['prefix' => 'content/tags', 'namespace' => 'Frontend'], function()
			{
				Route::get('/', 'TagsController@index');
			});
		});

		Route::group(['namespace' => 'Ninjaparade\Content\Controllers'], function()
		{
			Route::group(['prefix' => admin_uri().'/content/posttypes', 'namespace' => 'Admin'], function()
			{
				Route::get('/', ['as' => 'posttypes.index', 'uses' => 'PosttypesController@index']);
				Route::post('/', 'PosttypesController@executeAction');
				Route::get('grid', 'PosttypesController@grid');
				Route::get('create', 'PosttypesController@create');
				Route::post('create', 'PosttypesController@store');
				Route::get('{id}/edit', 'PosttypesController@edit');
				Route::post('{id}/edit', 'PosttypesController@update');
				Route::get('{id}/delete', 'PosttypesController@delete');
			});

			Route::group(['prefix' => 'content/posttypes', 'namespace' => 'Frontend'], function()
			{
				Route::get('/', 'PosttypesController@index');
			});
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

		'Ninjaparade\Content\Database\Seeds\ExtensionAttributesSeederTableSeeder',

	],

	/*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
	| List of permissions this extension has. These are shown in the user
	| management area to build a graphical interface where permissions
	| may be selected.
	|
	| The admin controllers state that permissions should follow the following
	| structure:
	|
	|    Vendor\Namespace\Controller@method
	|
	| For example:
	|
	|    Platform\Users\Controllers\Admin\UsersController@index
	|
	| These are automatically generated for controller routes however you are
	| free to add your own permissions and check against them at any time.
	|
	| When writing permissions, if you put a 'key' => 'value' pair, the 'value'
	| will be the label for the permission which is displayed when editing
	| permissions.
	|
	*/

	'permissions' => function()
	{
		return [
			'Ninjaparade\Content\Controllers\Admin\PostsController@index,grid'        => Lang::get('ninjaparade/content::posts/permissions.index'),
			'Ninjaparade\Content\Controllers\Admin\PostsController@create,store'      => Lang::get('ninjaparade/content::posts/permissions.create'),
			'Ninjaparade\Content\Controllers\Admin\PostsController@edit,update'       => Lang::get('ninjaparade/content::posts/permissions.edit'),
			'Ninjaparade\Content\Controllers\Admin\PostsController@delete'            => Lang::get('ninjaparade/content::posts/permissions.delete'),
			
			'Ninjaparade\Content\Controllers\Admin\AuthorsController@index,grid'      => Lang::get('ninjaparade/content::authors/permissions.index'),
			'Ninjaparade\Content\Controllers\Admin\AuthorsController@create,store'    => Lang::get('ninjaparade/content::authors/permissions.create'),
			'Ninjaparade\Content\Controllers\Admin\AuthorsController@edit,update'     => Lang::get('ninjaparade/content::authors/permissions.edit'),
			'Ninjaparade\Content\Controllers\Admin\AuthorsController@delete'          => Lang::get('ninjaparade/content::authors/permissions.delete'),
			
			'Ninjaparade\Content\Controllers\Admin\CategoriesController@index,grid'   => Lang::get('ninjaparade/content::categories/permissions.index'),
			'Ninjaparade\Content\Controllers\Admin\CategoriesController@create,store' => Lang::get('ninjaparade/content::categories/permissions.create'),
			'Ninjaparade\Content\Controllers\Admin\CategoriesController@edit,update'  => Lang::get('ninjaparade/content::categories/permissions.edit'),
			'Ninjaparade\Content\Controllers\Admin\CategoriesController@delete'       => Lang::get('ninjaparade/content::categories/permissions.delete'),
			
			'Ninjaparade\Content\Controllers\Admin\TagsController@index,grid'         => Lang::get('ninjaparade/content::tags/permissions.index'),
			'Ninjaparade\Content\Controllers\Admin\TagsController@create,store'       => Lang::get('ninjaparade/content::tags/permissions.create'),
			'Ninjaparade\Content\Controllers\Admin\TagsController@edit,update'        => Lang::get('ninjaparade/content::tags/permissions.edit'),
			'Ninjaparade\Content\Controllers\Admin\TagsController@delete'             => Lang::get('ninjaparade/content::tags/permissions.delete'),
			
			'Ninjaparade\Content\Controllers\Admin\PosttypesController@index,grid'    => Lang::get('ninjaparade/content::posttypes/permissions.index'),
			'Ninjaparade\Content\Controllers\Admin\PosttypesController@create,store'  => Lang::get('ninjaparade/content::posttypes/permissions.create'),
			'Ninjaparade\Content\Controllers\Admin\PosttypesController@edit,update'   => Lang::get('ninjaparade/content::posttypes/permissions.edit'),
			'Ninjaparade\Content\Controllers\Admin\PosttypesController@delete'        => Lang::get('ninjaparade/content::posttypes/permissions.delete'),
		];
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
							'name'  => 'All Posts',
							'class' => 'fa fa-circle-o',
							'uri'   => 'content/posts',
						],
						[
							'slug'  => 'admin-ninjaparade-create-content-post',
							'name'  => 'Add Post',
							'class' => 'fa fa-circle-o',
							'uri'   => 'content/posts/create',
						],
						[
							'slug'  => 'admin-ninjaparade-content-author',
							'name'  => 'All Authors',
							'class' => 'fa fa-circle-o',
							'uri'   => 'content/authors',
						],
						[
							'slug'  => 'admin-ninjaparade-create-content-author',
							'name'  => 'Add Author',
							'class' => 'fa fa-circle-o',
							'uri'   => 'content/authors/create',
						],
						[
							'slug'  => 'admin-ninjaparade-content-category',
							'name'  => 'All Categories',
							'class' => 'fa fa-circle-o',
							'uri'   => 'content/categories',
						],
						[
							'slug'  => 'admin-ninjaparade-create-content-category',
							'name'  => 'Add Categories',
							'class' => 'fa fa-circle-o',
							'uri'   => 'content/categories/create',
						],
						[
							'slug'  => 'admin-ninjaparade-content-tag',
							'name'  => 'All Tags',
							'class' => 'fa fa-circle-o',
							'uri'   => 'content/tags',
						],
						[
							'slug'  => 'admin-ninjaparade-create-content-tag',
							'name'  => 'All Tags',
							'class' => 'fa fa-circle-o',
							'uri'   => 'content/tags/create',
						],
						[
							'slug'  => 'admin-ninjaparade-content-posttype',
							'name'  => 'All Posttypes',
							'class' => 'fa fa-circle-o',
							'uri'   => 'content/posttypes',
						],
						[
							'slug'  => 'admin-ninjaparade-create-content-posttype',
							'name'  => 'Add Posttypes',
							'class' => 'fa fa-circle-o',
							'uri'   => 'content/posttypes/create',
						],
				],
			],
		],
		'main' => [
			[
				'slug' => 'main-ninjaparade-content',
				'name' => 'Content',
				'class' => 'fa fa-circle-o',
				'uri' => 'content',
			],
		],
	],

];
