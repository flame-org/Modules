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
use Tester\TestCase;

class ITracyBarPanelsProviderTest extends TestCase
{

	public function testPanelAvailability()
	{
		$reflectionProperty = new \ReflectionProperty(Debugger::getBar(), 'panels');
		$reflectionProperty->setAccessible(true);
		$panels = $reflectionProperty->getValue(Debugger::getBar());
		$reflectionProperty->setAccessible(false);
		Assert::true(isset($panels['BarPanel']));
	}

}

\run(new ITracyBarPanelsProviderTest);