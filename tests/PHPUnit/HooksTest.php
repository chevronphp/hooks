<?php

namespace HooksTestNS;

class TestLogger extends \Psr\Log\AbstractLogger {

	protected $output;

	function getOutput(){ return $this->output; }

	function log($level, $message, array $context = []){
		$this->output = "{$level},{$message}";
	}

}

class HooksTest extends \PHPUnit_Framework_TestCase {

	function test_register(){

		$ev = new \Chevron\Hooks\Hooks;

		$result = "";

		$func = function($arg1, $arg2)use(&$result){
			$result = "{$arg1}, {$arg2}";
		};

		$count = $ev->register("test.event", $func);

		$ev->dispatch("test.event", ["1234", "5678"]);

		$expected = "1234, 5678";

		$this->assertEquals(1, $count);
		$this->assertEquals($expected, $result);

	}

	function test_no_event(){

		$logger = new TestLogger;

		$ev = new \Chevron\Hooks\Hooks;

		$ev->setLogger($logger);

		$ev->dispatch("test.event");

		$output = $logger->getOutput();

		$expected = "notice,No event handlers registered for event: 'test.event'.";

		$this->assertEquals($expected, $output);

	}

}