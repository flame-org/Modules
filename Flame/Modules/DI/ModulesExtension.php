<?php
/**
 * Class ModulesExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 20.07.13
 */
namespace Flame\Modules\DI;

use Flame\Modules\Extension\NamedExtension;
use Flame\Modules\Providers\ILatteMacrosProvider;
use Flame\Modules\Providers\IRouterProvider;
use Flame\Modules\Providers\IPresenterMappingProvider;
use Nette\DI\ServiceDefinition;
use Nette\Utils\Validators;

class ModulesExtension extends NamedExtension
{

	/** @var array  */
	private $routes = array();

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$presenterFactory = $builder->getDefinition('nette.presenterFactory');
		$latte = $builder->getDefinition('nette.latte');

		foreach ($this->compiler->getExtensions() as $extension) {
			if ($extension instanceof IPresenterMappingProvider) {
				if ($mapping = $extension->getPresenterMapping()) {
					$presenterFactory->addSetup('setMapping', array($mapping));
				}
			}

			if ($extension instanceof IRouterProvider) {
				$this->routes = array_merge($this->routes, $extension->getRoutesDefinition());
			}

			if($extension instanceof ILatteMacrosProvider) {
				$this->setupTemplating($latte, $extension->getLatteMacros());
			}
		}

		$builder->addDefinition($this->prefix('routerFactory'))
			->setClass('Flame\Modules\Application\RouterFactory')
			->setArguments(array($this->routes));

		$builder->getDefinition('router')
			->setFactory('@' . $this->prefix('routerFactory') . '::createRouter');
	}

	/**
	 * @param ServiceDefinition $latte
	 * @param array $macros
	 * @return void
	 */
	private function setupTemplating(ServiceDefinition $latte, array $macros)
	{
		if(count($macros)) {
			foreach ($macros as $macro) {
				if (strpos($macro, '::') === FALSE && class_exists($macro)) {
					$macro .= '::install';

				} else {
					Validators::assert($macro, 'callable');
				}

				$latte->addSetup($macro . '(?->compiler)', array('@self'));
			}
		}
	}

}