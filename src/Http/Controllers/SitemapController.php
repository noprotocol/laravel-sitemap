<?php

namespace Noprotocol\LaravelSitemap\Http\Controllers;

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
        return response($this->sitemap->init(1)->get(), '200')
            ->header('Content-Type', 'text/xml');
    }
}
