<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Tests\Modules\Providers;

use Tester\Assert;
use Tester\TestCase;
use Tracy\Debugger;

require_once __DIR__ . '/../../bootstrap.php';

getContainer(TRUE);


class TracyBlueScreenProviderTest extends TestCase
{

	public function testPanelAvailability()
	{
		$reflectionProperty = new \ReflectionProperty(Debugger::getBlueScreen(), 'panels');
		$reflectionProperty->setAccessible(TRUE);
		$panels = $reflectionProperty->getValue(Debugger::getBlueScreen());
		Assert::contains('BlueScreenPanel::test', $panels);
	}

}

\run(new TracyBlueScreenProviderTest);
