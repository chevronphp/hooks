<?php

class EventLoaderTest extends PHPUnit_Framework_TestCase {

	function test_loadEvents(){
		$path = __DIR__ . "/helpers";
		$ev = (new \Chevron\Hooks\EventLoader)->loadEvents($path);

		$this->assertEquals($ev->countHandlers("event.one.test"), 1);

		$ev->dispatch("event.one.test");

		$this->assertEquals(EVENT_ONE_TESTED, true);
	}

	function test_loadEvents_args(){
		$path = __DIR__ . "/helpers";

		$obj = new stdClass;
		$ev = (new \Chevron\Hooks\EventLoader)->loadEvents($path);

		$this->assertEquals($ev->countHandlers("event.two.test"), 1);

		$ev->dispatch("event.two.test", [$obj]);

		$this->assertEquals($obj->tested, true);
	}

	/**
	 * @expectedException \Exception
	 */
	function test_loadEvents_Exception(){
		$path = __DIR__ . "/nothelpers";
		$ev = (new \Chevron\Hooks\EventLoader)->loadEvents($path);

		$this->assertEquals($ev->get("error"), 404);
		$this->assertEquals($ev->get("lambda"), 200);
	}

}