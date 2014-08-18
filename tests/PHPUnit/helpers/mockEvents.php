<?php

return function( \Chevron\Hooks\Interfaces\HooksInterface $evs){

	$evs->register("event.one.test", function(){
		define("EVENT_ONE_TESTED", true);
	});

	$evs->register("event.two.test", function($obj){
		$obj->tested = true;
	});

};