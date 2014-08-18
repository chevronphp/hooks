<?php

namespace Chevron\Hooks;
/**
 * A recursive service loader to parse a directory (recursivley) and load in PHP
 * file found. If the file returns a callable, it is called and passed a Deferred
 * container. Essentially, this class creates a Di, and loads it from a number of
 * service files. Each file should look like:
 *
 * <?php
 * return function($di){
 *     $di->set("nameOne", $value);
 *     $di->set("nameTwo", function() use ($di){});
 * }
 *
 * $value can be a callback (for singleton connections etc.)
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