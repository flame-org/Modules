<?php
/**
 * Class ModulesExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 20.07.13
 */
namespace Flame\Modules\DI;

use Flame\Modules\Extension\ModuleExtension;
use Flame\Modules\Providers\IRouterProvider;
use Flame\Modules\Providers\IPresenterMappingProvider;

class ModulesExtension extends ModuleExtension
{

	/** @var array  */
	private $routes = array();

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$presenterFactory = $builder->getDefinition('nette.presenterFactory');

		foreach ($this->compiler->getExtensions() as $extension) {
			if ($extension instanceof IPresenterMappingProvider) {
				if ($mapping = $extension->getPresenterMapping()) {
					$presenterFactory->addSetup('setMapping', array($mapping));
				}
			}

			if ($extension instanceof IRouterProvider) {
				$this->routes = array_merge($this->routes, $extension->getRoutesDefinition());
			}
		}

		$builder->addDefinition($this->prefix('routerFactory'))
			->setClass('Flame\Modules\Application\RouterFactory')
			->setArguments(array($this->routes));

		$builder->getDefinition('router')
			->setFactory('@' . $this->prefix('routerFactory') . '::createRouter');
	}

}