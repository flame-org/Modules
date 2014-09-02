<?php

require __DIR__ . '/../../vendor/autoload.php';


if (!class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

// create temporary directory
define('TEMP_DIR', __DIR__ . '/tmp/' . Nette\Utils\Random::generate(5));
Tester\Helpers::purge(TEMP_DIR);
Tracy\Debugger::$logDirectory = TEMP_DIR;

if (extension_loaded('xdebug')) {
	Tester\CodeCoverage\Collector::start(__DIR__ . '/coverage.dat');
}


function run(Tester\TestCase $testCase) {
	$testCase->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL);
}


function getContainer($debugMode = FALSE) {
	$configurator = new Nette\Configurator;
	$configurator->setTempDirectory(TEMP_DIR);
	$configurator->setDebugMode($debugMode);
	$configurator->addConfig(__DIR__ . '/config/config.neon');
	$configurator->createRobotLoader()
		->addDirectory(__DIR__ . '/data')
		->addDirectory(__DIR__ . '/Modules')
		->register();

	return $configurator->createContainer();
}
