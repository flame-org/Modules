<?php

$configurator = require __DIR__ . '/../app/configurator.php';

if (!class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

Tester\Environment::setup();

$configurator->setDebugMode(FALSE);
return $configurator->createContainer();
