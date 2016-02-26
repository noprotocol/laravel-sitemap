# Laravel sitemap

A dynamic sitemap creator for Laravel. Runs from a simple config and can be used to create multiple sitemaps. 
Comes in handy when running multiple domains and/or multiple models.


## How does it work

``` bash
composer install noprotocol/laravel-sitemap
php artisan vendor:publish --provider="Noprotocol\LaravelSitemap\SitemapServiceProvider"

```

Open the config/sitemap.php file and edit it. An example has been supplied

``` php
<?php

return [
	/*********************** EXAMPLE ************************
	'sites' => [
		1 => [
			// namespace to request from
			'\App\Page' => [ 

				// query eloquent inject
				'query' => function($query) { 
					return $query;
				},

				// the route to call
				'route' => 'page',

				// the database column where the slug (if any) resides in
				'slug' => 'slug',

				// the database column where the last updated date resides in
				'updated' => 'updated_at',
			],

			// urls that are not generated from DB are run straight through the xml generator
			'MANUAL' => [ 
				[
					'loc' => 'http://www.site.com/',
					'lastmod' => '2016-02-25',
				],
			],
		],

		2 => [
			'\App\Article' => [
				'query' => function($query) {
					return $query->where('something', '=', 'something');
				},
				'route' => 'page',

				// if the url consists of multiple parts, define them as array
				'slug' => [ 

					// first part of the slug, but it needs some functionality
					'category' => function($slug) { // the function to run with the url part before adding
						return str_slug($slug);
					},
					'slug' => false, // no function will run on this url part
				],
				'updated' => 'updated_at',
			],
		],
	]
	***************************** END EXAMPLE *****************************/

	'sites' => [
		1 => [
			// urls that are not generated from DB are run straight through the xml generator
			'MANUAL' => [ 
				[
					'loc' => 'http://www.site.com/',
					'lastmod' => '2016-02-25',
				],
			],
		],
	]

];
```

To the routes file add:

``` php
Route::get('sitemap.php', '\Noprotocol\LaravelSitemap\Http\Controllers\SitemapController@index');
```

As of this point you have a working sitemap.


If your feeling frisky or want to change settings you can create your own controller and point the route there

``` php
<?php

namespace App\Http\Controllers;

use Noprotocol\LaravelSitemap\Sitemap;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

class SitemapController extends Controller
{

    private $sitemap;

    public function __construct(Sitemap $sitemap) 
    {
        $this->sitemap = $sitemap;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response($this->sitemap->init(1)->get(false), '200')
            ->header('Content-Type', 'text/xml');
    }
}

```

