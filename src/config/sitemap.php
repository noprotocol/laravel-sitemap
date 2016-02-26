<?php

return [
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

];