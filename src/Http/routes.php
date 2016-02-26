<?php

// create dynamic sitemap
Route::get('sitemap.php', '\Noprotocol\LaravelSitemap\Http\Controllers\SitemapController@index');