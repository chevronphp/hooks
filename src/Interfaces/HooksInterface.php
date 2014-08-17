<?php

namespace Chevron\Hooks\Interfaces;
/**
 * defines the signature for Hooks
 * @package Chevron\Hooks
 */
interface HooksInterface {

	/**
	 * add method(s) to an event, if the event doesn't exist, register it
	 * @param string $event the event name
	 * @param callable $handler a callable to execute when the event is dispatched
	 * @param array $args optional arguments to pass to the callable
	 * @return int
	 */
	function register($event, callable $handler, array $args = []);

	/**
	 * execute all the events registered to an event, if the event doesn't exist
	 * log a notice
	 * @param string $event the event to dispatch
	 */
	function dispatch($event);

}