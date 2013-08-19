<?php

require __DIR__ . '/../libs/autoload.php';

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
