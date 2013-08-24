<?php
/**
 * Class RouterFactory
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 20.07.13
 */
namespace Flame\Modules\Application;

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;
use Nette\InvalidStateException;
use Nette\Reflection\ClassType;
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
			$definedRoutes = iterator_to_array($router);
			$router = new RouteList;
			$routes = array_reverse($routes);

			foreach ($routes as $route) {
				array_unshift($definedRoutes, static::createRoute($route));
			}

			if(count($definedRoutes)) {
				foreach ($definedRoutes as $route) {
					$router[] = $route;
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
		if(!is_array($route)) {
			throw new InvalidStateException('Route definition must be array, ' . gettype($route) . ' given');
		}

		$class = (string) key($route);
		$instance = new ClassType($class);
		return $instance->newInstanceArgs($route[$class]);
	}
}