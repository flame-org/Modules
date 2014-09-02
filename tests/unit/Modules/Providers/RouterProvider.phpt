<?php

namespace Flame\Tests\Modules\Providers;

use Flame\Tester\TestCase;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Ondřej Záruba
 */
class RouterProviderTest extends TestCase
{

	public function testRouterProvider()
	{
		/** @var RouteList[] $router */
		$router = $this->getContext()->getService('router');

		// 1. extension provider
		$routeList = $router[0];
		$routeList = $routeList->getIterator();

		/** @var NetteRouteMock $route */
		$route = $routeList[0];
		Assert::type('Flame\Modules\Application\Routers\NetteRouteMock', $route);
		Assert::same('test', $route->getRouter()->getMask());
		Assert::same('FlameTestPresenter', $route->getRouter()->getTargetPresenter());

		/** @var Route $route */
		$route = $routeList[1];
		Assert::type('Nette\Application\Routers\Route', $route);
		Assert::same('test2', $route->getMask());
		Assert::same('FlameModule:FlamePresenter', $route->getTargetPresenter());

		// 2. tagged service
		$routeList = $router[1];

		$baseRoute = $routeList[0];
		Assert::type('Nette\Application\Routers\Route', $baseRoute);
		Assert::same('<presenter>/<action>[/<id>]', $baseRoute->getMask());
	}

}

\run(new RouterProviderTest(getContainer()));
