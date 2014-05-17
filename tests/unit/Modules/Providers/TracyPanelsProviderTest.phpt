<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Tests\Modules\Providers;

require_once __DIR__ . '/../../bootstrap.php';

$configurator = new \Nette\Configurator();
$dir = __DIR__ . '/../../temp/' . \Nette\Utils\Random::generate(5);
mkdir($dir);
$configurator->setTempDirectory($dir)
	->addConfig(__DIR__ . '/../../data/config.neon');
$configurator->setDebugMode(true);
$configurator->createContainer();

use Tester\Assert;
use Tracy\Debugger;

class TracyPanelsProviderTest extends \Tester\TestCase
{

	public function testPanelAvailability()
	{
		$reflectionProperty = new \ReflectionProperty(Debugger::getBlueScreen(), 'panels');
		$reflectionProperty->setAccessible(true);
		$panels = $reflectionProperty->getValue(Debugger::getBlueScreen());
		$reflectionProperty->setAccessible(false);
		Assert::contains('BlueScreenPanel::test', $panels);
	}

}

\run(new TracyPanelsProviderTest);