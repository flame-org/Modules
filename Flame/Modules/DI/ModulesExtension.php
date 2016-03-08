<?php
/**
 * Class ModulesExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 20.07.13
 */
namespace Flame\Modules\DI;

use Flame\Modules\Configurators\ErrorPresenterConfig;
use Flame\Modules\Configurators\LatteMacrosConfig;
use Flame\Modules\Configurators\ParametersConfig;
use Flame\Modules\Configurators\PresenterMappingConfig;
use Flame\Modules\Configurators\TemplateHelpersConfig;
use Flame\Modules\Configurators\TracyBarPanelsConfig;
use Flame\Modules\Configurators\TracyPanelsConfig;
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
		// Loads all services tagged as router
		$this->addRouters();
	}

	/**
	 * @param Nette\PhpGenerator\ClassType $class
	 */
	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		$builder = $this->getContainerBuilder();

		if ($builder->parameters['debugMode']) {

			foreach ($this->compiler->getExtensions() as $extension) {
				if ($extension instanceof ITracyBarPanelsProvider) {
					$this->setupTracyBarPanels($extension, $class);
				}

				if ($extension instanceof ITracyPanelsProvider) {
					$this->setupTracyPanels($extension, $class);
				}
			}
		}
	}

	/**
	 * @param ITracyBarPanelsProvider $extension
	 * @param Nette\PhpGenerator\ClassType $class
	 * @throws Nette\Utils\AssertionException
	 */
	private function setupTracyBarPanels(ITracyBarPanelsProvider $extension, Nette\PhpGenerator\ClassType $class)
	{
		$config = new TracyBarPanelsConfig();
		$extension->setupTracyBarPanels($config);
		$panels = $config->getConfiguration();
		Validators::assert($panels, 'array');

		$builder = $this->getContainerBuilder();
		$initialize = $class->methods['initialize'];

		foreach ($panels as $item) {
			$initialize->addBody($builder->formatPhp(
				'Tracy\Debugger::getBar()->addPanel(?);',
				Nette\DI\Compiler::filterArguments(array(is_string($item) ? new Nette\DI\Statement($item) : $item))
			));
		}

	}

	private function setupTracyPanels(ITracyPanelsProvider $extension, Nette\PhpGenerator\ClassType $class)
	{
		$config = new TracyPanelsConfig();
		$extension->setupTracyPanels($config);
		$panels = $config->getConfiguration();

		$builder = $this->getContainerBuilder();
		$initialize = $class->methods['initialize'];

		foreach ($panels as $item) {
			$initialize->addBody($builder->formatPhp(
				'Tracy\Debugger::getBlueScreen()->addPanel(?);',
				Nette\DI\Compiler::filterArguments(array($item))
			));
		}
	}

	/**
	 * @param IParametersProvider $extension
	 * @throws Nette\Utils\AssertionException
	 */
	private function setupParameters(IParametersProvider $extension)
	{
		$config = new ParametersConfig();
		$extension->setupParameters($config);
		$parameters = $config->getConfiguration();
		Validators::assert($parameters, 'array', 'parameters');

		$builder = $this->getContainerBuilder();
		if (count($parameters)) {
			$builder->parameters = Nette\DI\Config\Helpers::merge($builder->expand($parameters), $builder->parameters);
		}
	}

	/**
	 * @param ILatteMacrosProvider $extension
	 * @throws Nette\Utils\AssertionException
	 */
	private function setupMacros(ILatteMacrosProvider $extension)
	{
		$config = new LatteMacrosConfig();
		$extension->setupMacros($config);
		$macros = $config->getConfiguration();

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

	/**
	 * @param ITemplateHelpersProvider $extension
	 * @throws Nette\Utils\AssertionException
	 */
	private function setupHelpers(ITemplateHelpersProvider $extension)
	{

		$config = new TemplateHelpersConfig();
		$extension->setupHelpers($config);
		$helpers = $config->getConfiguration();
		Validators::assert($helpers, 'array', 'helpers');

		$builder = $this->getContainerBuilder();
		$latteFactory = $this->getLatteFactory();
		if (count($helpers)) {
			foreach ($helpers as $name => $helper) {
				if (is_string($helper) && !is_string($name)) {
					$provider = $builder->addDefinition($this->prefix('helperProvider.' . $config->getUniqueId() . '.' . $name))
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

	/**
	 * @param IPresenterMappingProvider $extension
	 * @throws Nette\Utils\AssertionException
	 */
	private function setupPresenterMapping(IPresenterMappingProvider $extension)
	{
		$config = new PresenterMappingConfig();
		$extension->setupPresenterMapping($config);
		$mapping = $config->getConfiguration();
		Validators::assert($mapping, 'array', 'mapping');

		if (count($mapping)) {
			$this->getContainerBuilder()->getDefinition('nette.presenterFactory')
				->addSetup('setMapping', array($mapping));
		}
	}

	/**
	 * @param IRouterProvider $extension
	 */
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

	/**
	 * @param IErrorPresenterProvider $extension
	 * @throws Nette\Utils\AssertionException
	 */
	private function setupErrorPresenter(IErrorPresenterProvider $extension)
	{
		$config = new ErrorPresenterConfig();
		$extension->setupErrorPresenter($config);
		$presenterName = $config->getConfiguration();
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

	/**
	 * Loads all services tagged as router
	 * and adds them to router service
	 *
	 * @author: Adam Kadlec <adam.kadlec@gmail.com>
	 * @date: 08.10.14
	 */
	private function addRouters()
	{
		$builder = $this->getContainerBuilder();

		// Get application router
		$router = $builder->getDefinition('router');

		// Init collections
		$routerFactories = array();

		foreach ($builder->findByTag(self::TAG_ROUTER) as $serviceName => $priority) {
			// Priority is not defined...
			if (is_bool($priority)) {
				// ...use default value
				$priority = 100;
			}

			$routerFactories[$priority][$serviceName] = $serviceName;
		}

		// Sort routes by priority
		if (!empty($routerFactories)) {
			krsort($routerFactories, SORT_NUMERIC);

			foreach ($routerFactories as $priority => $items) {
				$routerFactories[$priority] = $items;
			}

			// Process all routes services by priority...
			foreach ($routerFactories as $priority => $items) {
				// ...and by service name...
				foreach($items as $serviceName) {
					$factory = new Nette\DI\Statement(array('@' . $serviceName, 'createRouter'));
					$router->addSetup('offsetSet', array(NULL, $factory));
				}
			}
		}
	}
}
