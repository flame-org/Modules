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
use Nette\DI\ContainerBuilder;
use Nette\DI\ServiceDefinition;
use Nette\Framework;
use Nette\Utils\Validators;

class ModulesExtension extends NamedExtension
{

	/** @var array  */
	private $routes = array();

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$this->setupPresenterFactory($builder);

		$presenterFactory = $builder->getDefinition('nette.presenterFactory');
		$latte = $builder->getDefinition('nette.latte');

		foreach ($this->compiler->getExtensions() as $extension) {
			if ($extension instanceof IPresenterMappingProvider) {
				if ($mapping = $extension->getPresenterMapping()) {
					$presenterFactory->addSetup('setMapping', array($mapping));
				}
			}

			if ($extension instanceof IRouterProvider) {
				$this->routes = array_merge($this->routes, (array) $extension->getRoutesDefinition());
			}

			if($extension instanceof ILatteMacrosProvider) {
				$this->setupTemplating($latte, $extension->getLatteMacros());
			}
		}

		if(count($this->routes)) {
			$builder->getDefinition('router')
				->addSetup('Flame\Modules\Application\RouterFactory::prependTo($service, ?)', array($this->routes));
		}
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

	/**
	 * @param ContainerBuilder $builder
	 * @return void
	 */
	private function setupPresenterFactory(ContainerBuilder &$builder)
	{
		if(version_compare(Framework::VERSION, '2.1-dev', '<')) {
			$builder->removeDefinition('nette.presenterFactory');
			$presenterFactory = $builder->addDefinition('nette.presenterFactory')
				->setClass('Flame\Modules\Application\PresenterFactory', array('%appDir%'))
				->setAutowired(TRUE)
				->setShared(TRUE);

			if (isset($this->compiler->config['nette']['application']['mapping'])) {
				$presenterFactory->addSetup('setMapping', array($this->compiler->config['nette']['application']['mapping']));
			}
		}
	}
}