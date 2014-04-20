<?php

namespace Flame\Tests\Modules;

use Flame\Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Ondřej Záruba
 */
class RouterProviderTest extends TestCase
{
	public function testRouterProvider()
	{
		/** @var \Nette\Application\Application $application */
		$application = $this->getContext()->getByType('Nette\Application\Application');
		$routeList = $application->getRouter();
		Assert::same('test', $routeList[0]->getMask());
		Assert::same('test2', $routeList[1]->getMask());
		Assert::same('FlameTestPresenter', $routeList[0]->getTargetPresenter());
		Assert::same('FlameModule:FlamePresenter', $routeList[1]->getTargetPresenter());
	}
}

\run(new RouterProviderTest(getContainer()));