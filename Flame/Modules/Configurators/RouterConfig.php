<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class RouterConfig extends Config implements IRouterConfig
{

	/** @var  null|RouteList */
	private $routeList;

	/** @var  null|string */
	private $rootModuleName;

	/**
	 * @return mixed
	 */
	public function getConfiguration()
	{
		return $this->routeList;
	}

	/**
	 * @param string $serviceName
	 * @return $this
	 */
	public function addRouterService($serviceName)
	{
		// TODO: Implement addRouterService() method.
	}

	/**
	 * @param IRouter $router
	 * @return $this
	 */
	public function addRoute(IRouter $router)
	{
		$this->routeList[] = $router;
		return $this;
	}

	/**
	 * @param mixed $mask
	 * @param array $metadata
	 * @param int $flags
	 * @return $this
	 */
	public function addStandardRoute($mask, $metadata = array(), $flags = 0)
	{
		$this->routeList[] = new Route($mask, $metadata, $flags);
		return $this;
	}

	private function getRouteList()
	{
		if($this->routeList === null) {
			$this->routeList = new RouteList($this->rootModuleName);
		}

		return $this->routeList;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setRootRouteModuleName($name)
	{
		$this->rootModuleName = (string) $name;
		return $this;
	}


} 