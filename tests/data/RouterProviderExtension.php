<?php

/**
 * @author Ondřej Záruba
 */
class RouterProviderExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\IRouterProvider
{
	/**
	 * Returns array of ServiceDefinition,
	 * that will be appended to setup of router service
	 *
	 * @example
	 * return array(
	 *      array('Nette\Application\Routers\Route' => array('/', array(
	 *          'presenter' => 'Homepage',
	 *          'action' => 'default'
	 *      )))
	 * );
	 */
	public function getRoutesDefinition()
	{
		return array(
			new \Flame\Modules\Application\Routers\NetteRouteMock('test', 'FlameTestPresenter:'),
			array('Nette\Application\Routers\Route' => array('test2', array(
				'module' => 'FlameModule',
				'presenter' => 'FlamePresenter',
			)))
		);
	}
}