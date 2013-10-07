<?php
/**
 * Class RouterFactory
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 20.07.13
 */
namespace Flame\Modules\Application;

use Flame\Modules\Application\Routers\IRouteMock;
use Flame\Modules\Application\Routers\RouteMock;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;
use Nette\InvalidStateException;
use Nette;

class RouterFactory
{

	/**
	 * @throws \Nette\StaticClassException
	 */
	public function __constructor()
	{
		throw new Nette\StaticClassException;
	}

	/**
	 * @param Nette\Application\IRouter $router
	 * @param array $routes
	 * @throws \Nette\Utils\AssertionException
	 */
	public static function prependTo(Nette\Application\IRouter &$router, array $routes)
	{
		if (!$router instanceof RouteList) {
			throw new Nette\Utils\AssertionException(
				'If you want to use Flame/Modules then your main router ' .
				'must be an instance of Nette\Application\Routers\RouteList'
			);
		}

		if(count($routes)) {
			$definedRoutes = array_merge(iterator_to_array($router), $routes);
			$router = new RouteList;

			foreach ($definedRoutes as $route) {
				if($route instanceof Nette\Application\IRouter) {
					$router[] = $route;
				}else{
					$router[] = static::createRoute($route);
				}
			}
		}
	}

	/**
	 * @param $route
	 * @return object
	 * @throws \Nette\InvalidStateException
	 */
	private static function createRoute($route)
	{
		if(is_array($route) && count($route) >= 1) {
			$class = key($route);
			$route = new RouteMock($class, $route[$class]);
		}

		if($route instanceof IRouteMock) {
			return $route->getRouter();
		}

		throw new InvalidStateException('Route definition must be array or instance of Flame\Modules\Application\Routers\IRouteMock, ' . gettype($route) . ' given');
	}
}
