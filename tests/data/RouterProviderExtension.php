<?php

use Flame\Modules\DI\ModulesExtension;
use Nette\Application\Routers\RouteList;


/**
 * @author Ondřej Záruba
 */
class RouterProviderExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\IRouterProvider
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition('service.routerFactory')
			->setClass('Modules\RouterFactory')
			->addTag(ModulesExtension::TAG_ROUTER);
	}


	/**
	 * @return \Nette\Application\IRouter
	 */
	public function getRoutesDefinition()
	{
		$routeList = new RouteList;
		$routeList[] = new Flame\Modules\Application\Routers\NetteRouteMock('test', 'FlameTestPresenter:');
		$routeList[] = new Nette\Application\Routers\Route('test2', array(
			'module' => 'FlameModule',
			'presenter' => 'FlamePresenter'
		));
		return $routeList;
	}

}
