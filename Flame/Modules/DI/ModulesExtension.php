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

		if(count($extensions = $this->compiler->getExtensions())) {
			$presenterFactory = $builder->getDefinition('nette.presenterFactory');
			$latte = $builder->getDefinition('nette.latte');

			foreach ($extensions as $extension) {
				if ($extension instanceof IPresenterMappingProvider) {
					$this->setupPresenterMapping($presenterFactory, $extension);
				}

				if ($extension instanceof IRouterProvider) {
					$this->setupRouter($extension);
				}

				if($extension instanceof ILatteMacrosProvider) {
					$this->setupMacros($latte, $extension);
				}
			}
		}

		if(count($this->routes)) {
			$builder->getDefinition('router')
				->addSetup('Flame\Modules\Application\RouterFactory::prependTo($service, ?)', array($this->routes));
		}
	}

	/**
	 * @param ContainerBuilder $builder
	 * @return void
	 */
	protected function setupPresenterFactory(ContainerBuilder &$builder)
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

	/**
	 * @param ServiceDefinition $latte
	 * @param ILatteMacrosProvider $extension
	 * @return void
	 */
	private function setupMacros(ServiceDefinition $latte, ILatteMacrosProvider $extension)
	{
		$macros = $extension->getLatteMacros();
		Validators::assert($macros, 'array');

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
	 * @param ServiceDefinition $presenterFactory
	 * @param IPresenterMappingProvider $extension
	 * @return void
	 */
	private function setupPresenterMapping(ServiceDefinition $presenterFactory, IPresenterMappingProvider $extension)
	{
		$mapping = $extension->getPresenterMapping();
		Validators::assert($mapping, 'array');

		if (count($mapping)) {
			$presenterFactory->addSetup('setMapping', array($mapping));
		}
	}

	/**
	 * @param IRouterProvider $extension
	 * @return void
	 */
	private function setupRouter(IRouterProvider $extension)
	{
		$routes = $extension->getRoutesDefinition();
		Validators::assert($routes, 'array');

		if (count($routes)) {
			$this->routes = array_merge($this->routes, $routes);
		}
	}
}