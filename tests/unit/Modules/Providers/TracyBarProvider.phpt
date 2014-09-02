<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Tests\Modules\Providers;

use Tester\Assert;
use Tracy\Debugger;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

getContainer(TRUE);


class TracyBarProviderTest extends TestCase
{

	public function testPanelAvailability()
	{
		$barPanel = Debugger::getBar()->getPanel('BarPanel');
		Assert::type('BarPanel', $barPanel);
	}

}

\run(new TracyBarProviderTest);
