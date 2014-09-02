<?php

require __DIR__ . '/../../vendor/autoload.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode(TRUE);  // debug mode MUST NOT be enabled on production server
$configurator->enableDebugger(__DIR__ . '/log');

$configurator->setTempDirectory(__DIR__ . '/temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/app/config/config.neon');

if (!class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

Tester\Environment::setup();

$configurator->setDebugMode(FALSE);
return $configurator->createContainer();
