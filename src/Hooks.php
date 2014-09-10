<?php

namespace Chevron\Hooks;

use \Psr\Log;
/**
 * allow for the registration and dispatching of callables using events
 * @package Chevron\Hooks
 */
class Hooks implements Log\LoggerAwareInterface, Interfaces\HooksInterface {

	use Log\LoggerAwareTrait;

	/**
	 * the array key for the functions, to avoid strings
	 */
	const FUNC_KEY = "func";

	/**
	 * the array key for the functions, to avoid strings
	 */
	const HOOK_STOP = 90053;

	/**
	 * map of events, functions, args
	 */
	protected $events = [];

	/**
	 * add method(s) to an event, if the event doesn't exist, register it
	 * @param string $event the event name
	 * @param callable $handler a callable to execute when the event is dispatched
	 * @return int
	 */
	function register($event, callable $handler){
		$this->events[$event][] = [
			static::FUNC_KEY => $handler
		];
		return count($this->events[$event]);
	}

	/**
	 * execute all the events registered to an event, if the event doesn't exist
	 * log a notice
	 * @param string $event the event to dispatch
	 * @param array $args optional arguments to pass to the callable
	 */
	function dispatch($event, array $args = []){
		if($handlers = $this->getHandlers($event)){
			foreach ($handlers as $handler) {
				// pass args to the event at the time of dispatch
				$stop = call_user_func_array($handler[static::FUNC_KEY], $args);
				if($stop == static::HOOK_STOP){
					break;
				}
			}
		}else{
			if($this->logger InstanceOf Log\LoggerInterface){
				$this->logger->notice("No event handlers registered for event: '{$event}'.", []);
			}
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

}