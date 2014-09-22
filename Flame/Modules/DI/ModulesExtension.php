<?php
/**
 * Class ModulesExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 20.07.13
 */
namespace Flame\Modules\DI;

use Flame\Modules\Providers\IErrorPresenterProvider;
use Flame\Modules\Providers\ILatteMacrosProvider;
use Flame\Modules\Providers\IParametersProvider;
use Flame\Modules\Providers\IRouterProvider;
use Flame\Modules\Providers\IPresenterMappingProvider;
use Flame\Modules\Providers\ITemplateHelpersProvider;
use Flame\Modules\Providers\ITracyBarPanelsProvider;
use Flame\Modules\Providers\ITracyPanelsProvider;
use Nette;
use Nette\DI\ServiceDefinition;
use Nette\Utils\Validators;


class ModulesExtension extends Nette\DI\CompilerExtension
{
	const TAG_ROUTER = 'flame.modules.router';


	public function loadConfiguration()
	{
		foreach ($this->compiler->getExtensions() as $extension) {
			if ($extension instanceof IParametersProvider) {
				$this->setupParameters($extension);
			}

			if ($extension instanceof IPresenterMappingProvider) {
				$this->setupPresenterMapping($extension);
			}

			if ($extension instanceof IRouterProvider) {
				$this->setupRouter($extension);
			}

			if ($extension instanceof ILatteMacrosProvider) {
				$this->setupMacros($extension);
			}

			if ($extension instanceof ITemplateHelpersProvider) {
				$this->setupHelpers($extension);
			}

			if ($extension instanceof IErrorPresenterProvider){
				$this->setupErrorPresenter($extension);
			}
		}
	}

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		// load all services tagged as router and add them to router service
		$router = $builder->getDefinition('router');
		foreach (array_keys($builder->findByTag(self::TAG_ROUTER)) as $serviceName) {
			$factory = new Nette\DI\Statement(array('@' . $serviceName, 'createRouter'));
			$router->addSetup('offsetSet', array(NULL, $factory));
		}
	}

	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		$builder = $this->getContainerBuilder();

		if ($builder->parameters['debugMode']) {
			$initialize = $class->methods['initialize'];

			foreach ($this->compiler->getExtensions() as $extension) {
				if ($extension instanceof ITracyBarPanelsProvider) {
					foreach ($extension->getTracyBarPanels() as $item) {
						$initialize->addBody($builder->formatPhp(
							'Tracy\Debugger::getBar()->addPanel(?);',
							Nette\DI\Compiler::filterArguments(array(is_string($item) ? new Nette\DI\Statement($item) : $item))
						));
					}
				}

				if ($extension instanceof ITracyPanelsProvider) {
					foreach ($extension->getTracyPanels() as $item) {
						$initialize->addBody($builder->formatPhp(
							'Tracy\Debugger::getBlueScreen()->addPanel(?);',
							Nette\DI\Compiler::filterArguments(array($item))
						));
					}
				}
			}
		}
	}

	private function setupParameters(IParametersProvider $extension)
	{
		$parameters = $extension->getParameters();
		Validators::assert($parameters, 'array', 'parameters');

		$builder = $this->getContainerBuilder();
		if (count($parameters)) {
			$builder->parameters = Nette\DI\Config\Helpers::merge($builder->expand($parameters), $builder->parameters);
		}
	}

	private function setupMacros(ILatteMacrosProvider $extension)
	{
		$macros = $extension->getLatteMacros();
		Validators::assert($macros, 'array', 'macros');

		$latteFactory = $this->getLatteFactory();
		foreach ($macros as $macro) {
			if (strpos($macro, '::') === FALSE && class_exists($macro)) {
				$macro .= '::install';

			} else {
				Validators::assert($macro, 'callable', 'macro');
			}

			$latteFactory->addSetup('?->onCompile[] = function($engine) { ' . $macro . '($engine->getCompiler()); }', array('@self'));
		}
	}

	private function setupHelpers(ITemplateHelpersProvider $extension)
	{
		$helpers = $extension->getHelpersConfiguration();
		Validators::assert($helpers, 'array', 'helpers');

		$builder = $this->getContainerBuilder();
		$latteFactory = $this->getLatteFactory();
		if (count($helpers)) {
			foreach ($helpers as $name => $helper) {
				if (is_string($helper) && !is_string($name)) {
					$provider = $builder->addDefinition($this->prefix('helperProvider.' . $name))
						->setClass($helper);

					$latteFactory->addSetup('Flame\Modules\Template\Helper::register($service, ?)', array($provider));

				} else {
					Validators::assert($name, 'string', 'name');
					Validators::assert($helper, 'string|array', 'helper');

					$latteFactory->addSetup('addFilter', array($name, $helper));
				}
			}
		}
	}

	private function setupPresenterMapping(IPresenterMappingProvider $extension)
	{
		$mapping = $extension->getPresenterMapping();
		Validators::assert($mapping, 'array', 'mapping');

		if (count($mapping)) {
			$this->getContainerBuilder()->getDefinition('nette.presenterFactory')
				->addSetup('setMapping', array($mapping));
		}
	}

	private function setupRouter(IRouterProvider $extension)
	{
		$builder = $this->getContainerBuilder();
		$router = $builder->getDefinition('router');

		/** @var Nette\DI\CompilerExtension $extension */
		$name = $this->addRouteService($extension->getReflection()->name);
		$router->addSetup('offsetSet', array(NULL, $name));
	}

	/**
	 * @param string $class
	 * @return string
	 */
	private function addRouteService($class)
	{
		$serviceName = md5($class);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('routeService.' . $serviceName))
			->setClass($class)
			->setInject(TRUE);

		$builder->addDefinition('routerServiceFactory.' . $serviceName)
			->setFactory($this->prefix('@routeService.' . $serviceName) . '::getRoutesDefinition')
			->setAutowired(FALSE);

		return '@routerServiceFactory.' . $serviceName;
	}

	private function setupErrorPresenter(IErrorPresenterProvider $extension)
	{
		$presenterName = $extension->getErrorPresenterName();
		Validators::assert($presenterName, 'string', 'presenterName');

		$builder = $this->getContainerBuilder();
		$builder->getDefinition('application')
			->addSetup('$errorPresenter', array($presenterName));
	}


	/**
	 * @return ServiceDefinition
	 */
	private function getLatteFactory()
	{
		$builder = $this->getContainerBuilder();
		return $builder->hasDefinition('nette.latteFactory')
			? $builder->getDefinition('nette.latteFactory')
			: $builder->getDefinition('nette.latte');
	}

}
