<?php

require __DIR__ . '/../libs/autoload.php';
require __DIR__ . '/data/ErrorPresenterExtension.php';
require __DIR__ . '/data/PresenterMappingExtension.php';
require __DIR__ . '/data/RouterProviderExtension.php';
require __DIR__ . '/data/LatteMacrosProviderExtension.php';
require __DIR__ . '/data/TemplateHelpersProviderExtension.php';
require __DIR__ . '/data/TracyBlueScreenProviderExtension.php';
require __DIR__ . '/data/TracyBarProviderExtension.php';
require __DIR__ . '/data/TestMacro.php';
require __DIR__ . '/data/BarPanel.php';
require __DIR__ . '/data/BlueScreenPanel.php';
require __DIR__ . '/data/TestHelper.php';
require __DIR__ . '/data/TestHelper2.php';


if (!class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

\Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

if (extension_loaded('xdebug')) {
	Tester\CodeCoverage\Collector::start(__DIR__ . '/coverage.dat');
}

/**
 * @param \Tester\TestCase $testCase
 */
function run(\Tester\TestCase $testCase) {
	$testCase->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL);
}

function getContainer() {

	$configurator = new \Nette\Configurator();
	$dir = __DIR__ . '/temp/' . \Nette\Utils\Random::generate(5);
	mkdir($dir, 0777, true);
	$configurator->setTempDirectory($dir)
		->addConfig(__DIR__ . '/data/config.neon');
	$container = $configurator->createContainer();
	return $container;
}
