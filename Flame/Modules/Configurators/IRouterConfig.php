<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

use Nette\Application\IRouter;

interface IRouterConfig
{

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setRootRouteModuleName($name);

	/**
	 * @param string $serviceName
	 * @return $this
	 */
	public function addRouterService($serviceName);

	/**
	 * @param IRouter $router
	 * @return $this
	 */
	public function addRoute(IRouter $router);

	/**
	 * @param mixed $mask
	 * @param array $metadata
	 * @param int $flags
	 * @return $this
	 */
	public function addStandardRoute($mask, $metadata = array(), $flags = 0);
} 