<?php

namespace Chevron\Hooks;

use \Psr\Log;
/**
 * allow for the registration and dispatching of callables using events
 * @package Chevron\Hooks
 */
class Hooks implements Log\LoggerAwareInterface {

	use Log\LoggerAwareTrait;

	/**
	 * the array key for the functions, to avoid strings
	 */
	const FUNC_KEY = "func";

	/**
	 * the array key for the functions' arguments, to avoid strings
	 */
	const ARGS_KEY = "args";

	/**
	 * map of events, functions, args
	 */
	protected $events = [];

	/**
	 * add method(s) to an event, if the event doesn't exist, register it
	 * @param string $event the event name
	 * @param callable $handler a callable to execute when the event is dispatched
	 * @param array $args optional arguments to pass to the callable
	 * @return int
	 */
	function register($event, callable $handler, array $args = []){
		$this->events[$event][] = [
			static::FUNC_KEY => $handler,
			static::ARGS_KEY => $args,
		];
		return count($this->events[$event]);
	}

	/**
	 * execute all the events registered to an event, if the event doesn't exist
	 * log a notice
	 * @param string $event the event to dispatch
	 */
	function dispatch($event){
		if($handlers = $this->getHandlers($event)){
			foreach ($handlers as $handler) {
				call_user_func_array($handler[static::FUNC_KEY], $handler[static::ARGS_KEY]);
			}
		}else{
			$this->getLogger()->notice("No event handlers registered for event: '{$event}'.", []);
		}
	}

	/**
	 * get the number of callables registered to an event
	 * @param string $event the event in question
	 */
	function countHandlers($event){
		$count = 0;
		if(array_key_exists($event, $this->events)){
			$count = count($this->events[$event]);
		}
		return $count;
	}

	/**
	 * get all the handlers for an event
	 */
	protected function getHandlers($event){
		if(!$this->countHandlers($event)){
			return [];
		}

		if(!is_array($this->events[$event])){
			return [];
		}

		return $this->events[$event];
	}

	/**
	 * get the current logger, defaults to NullLogger
	 */
	function getLogger(){
		if(!$this->logger){
			$this->logger = new Log\NullLogger;
		}
		return $this->logger;
	}

}