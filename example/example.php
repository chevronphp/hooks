<?php

require "vendor/autoload.php";

$ev = new \Chevron\Hooks\Hooks;

$ev->register("cycle.pre", function(){ echo "IN THE BEGINNING ...\n\n"; });
$ev->register("cycle.mid", function(){ echo "IN THE MIDDLE ...\n\n"; });
$ev->register("cycle.post", function(){ echo "IN THE END ...\n\n"; });


$app = function()use($ev){
	echo "First, we say something like:\n\n";
	$ev->dispatch("cycle.pre");
	echo "Next, we might say something like:\n\n";
	$ev->dispatch("cycle.mid");
	echo "Then we say something like:\n\n";
	$ev->dispatch("cycle.post");
};

$app();