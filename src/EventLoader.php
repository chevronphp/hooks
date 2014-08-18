<?php

namespace Chevron\Hooks;
/**
 * A recursive event loader to parse a directory (recursivley) and load in PHP
 * files found. If the file returns a callable, it is called and passed an instance
 * of Hooks. Essentially, this class creates a Hooks, and loads it from a number of
 * event files. Each file should look like:
 *
 * <?php
 * return function($ev){
 *     $ev->register("event.one", function(){  });
 * }
 *
 * $value must be a callback
 *
 * @package Chevron\Hooks
 */
class EventLoader {

	protected function getPaths($path){

		if(!is_dir($path)){
			throw new \Exception("EventLoader::getPaths() ... {$path} is not a directory.");
		}

		$iter = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator(
				rtrim($path, DIRECTORY_SEPARATOR)
			)
		);

		$files = [];
		foreach($iter as $path => $file){
			if(substr($path, -4) === ".php"){
				$files[] = $path;
			}
		}
		return $files;
	}

	function loadEvents($path){

		$ev   = new Hooks;

		$files = $this->getPaths($path);

		foreach($files as $file){
			$evs = require $file;
			if(is_callable($evs)){
				call_user_func($evs, $ev);
			}
		}

		return $ev;

	}

}