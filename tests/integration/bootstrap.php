<?php

require __DIR__ . '/../../vendor/autoload.php';

if (!class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

Tester\Environment::setup();

function getContainer($debugMode = FALSE)
{

	$configurator = new Nette\Configurator;

	$configurator->enableDebugger(__DIR__ . '/log');
	$configurator->setTempDirectory(__DIR__ . '/temp');

	$configurator->createRobotLoader()
		->addDirectory(__DIR__)
		->register();

	$configurator->addConfig(__DIR__ . '/app/config/config.neon');

	$configurator->setDebugMode($debugMode);
	return $configurator->createContainer();
}
