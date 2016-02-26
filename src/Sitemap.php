<?php

namespace Noprotocol\LaravelSitemap;

use Illuminate\Foundation\Application as App;
use Config;
use Cache;
use URL;

class Sitemap {
	
	private $app;

	private $xml;

	private $configKey;

	private $config;

	private $date;

	private $interval;

	private $priority; 

	private $cache;

	private $cacheKey;

	/**
	 * Initiate the sitemap
	 */
	public function init($configKey) 
	{
		$this->configKey = $configKey;
		array_push($this->cacheKey, $configKey);
		return $this;
	}

	// always, hourly, daily, weekly, monthly, yearly, never
	public function interval($interval)
	{
		$this->interval = $interval;
		array_push($this->cacheKey, $interval);
		return $this;
	}


	public function cache($minutes)
	{
		$this->cache = $minutes;
		array_push($this->cacheKey, $minutes);
		return $this;
	}


	public function get($cache = true) 
	{
		if($cache) {
			return Cache::remember(serialize($this->cacheKey), $this->cache, function() {
				return $this->getConfig()
					->runQueries();
			});
		}
		else {
			return $this->getConfig()
				->runQueries();
		}
	}



	public function __construct(App $app) 
	{
		$this->app = $app;
		$this->xml = '';
		$this->interval = 'daily';
		$this->priority = '0.5';
		$this->cacheKey = [];
		$this->cache = 60;
		$this->configKey = 1;
	}
	

	private function getConfig()
	{
		$this->config = Config::get('sitemap.sites.'.$this->configKey);
		return $this;
	}


	private function runQueries()
	{
		$sitemap = '';

		foreach($this->config as $namespace => $config) {

			if($namespace == 'MANUAL') {
				foreach($config as $xmlArray) {
					$this->xml .= $this->makeXml($xmlArray+[
						'changefreq' => $this->interval,
						'priority' => $this->priority,
					]);
				}
			}
			else {
				// initiate the model
				$model = $this->app->make($namespace);

				// run the query from the config
				$items = $config['query']($model)->get();

				$sitemap = $this->formatData($items, $config);
			}
		}

		return $this->addHeader($this->xml);
	}


	private function formatData($items, $config)
	{
		foreach($items as $item) {
			$xmlArray = [
				'loc' => $this->buildUrl($item, $config),
				'lastmod' => date('Y-m-d', strtotime($item->{$config['updated']})),
				'changefreq' => $this->interval,
				'priority' => $this->priority,
			];

			$this->xml .= $this->makeXml($xmlArray);
		}
	}


	private function makeXml($xmlArray)
	{
		$xml = '<url>'.PHP_EOL;

		foreach($xmlArray as $key => $value) {
			$xml .= '<'.$key.'>'.$value.'</'.$key.'>'.PHP_EOL;
		}

		$xml .= '</url>'.PHP_EOL;

		return $xml;
	}


	private function buildUrl($item, $config) 
	{
		$slug = '';
		if(is_array($config['slug'])) {
			foreach($config['slug'] as $key => $function) {
				if(is_callable($function)) {
					$slug .= $function($item->{$key});
				}
				else {
					$slug .= $item->{$key};
				}
				$slug .= '/';
			}
		}
		else {
			$slug .= $item->{$config['slug']};
		}

		return URL::route($config['route'], ['slug' => $slug]);
	}


	private function addHeader($xmlData)
	{
		$sitemap = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
		$sitemap .= '<urlset'.PHP_EOL;
		$sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'.PHP_EOL;
		$sitemap .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'.PHP_EOL;
		$sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9'.PHP_EOL;
		$sitemap .= 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'.PHP_EOL;
		$sitemap .= $xmlData;
		$sitemap .= '</urlset>'.PHP_EOL;

		return $sitemap;
	}


	

              
}