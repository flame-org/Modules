<?php
/**
 * Class ModulesExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 20.07.13
 */
namespace Flame\Modules\DI;

use Flame\Modules\Extension\ModuleExtension;

class ModulesExtension extends ModuleExtension
{

	/** @var array  */
	private $routes = array();

	/**
	 * @param array $routes
	 */
	public function setRouteList(array $routes)
	{
		$this->routes = $routes;
	}

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('routerFactory'))
			->setClass('Flame\Modules\Application\RouterFactory')
			->setArguments(array($this->routes));

		$builder->getDefinition('router')
			->setFactory('@' . $this->prefix('routerFactory') . '::createRouter');
	}

}