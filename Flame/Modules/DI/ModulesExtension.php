<?php
/**
 * Class ModulesExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 20.07.13
 */
namespace Flame\Modules\DI;

use Flame\Modules\Application\Routers\NetteRouteListMock;
use Flame\Modules\Application\Routers\NetteRouteMock;
use Flame\Modules\Application\Routers\RouteMock;
use Flame\Modules\Providers\IErrorPresenterProvider;
use Flame\Modules\Providers\ILatteMacrosProvider;
use Flame\Modules\Providers\IParametersProvider;
use Flame\Modules\Providers\IRouterProvider;
use Flame\Modules\Providers\IPresenterMappingProvider;
use Flame\Modules\Providers\ITemplateHelpersProvider;
use Flame\Modules\Providers\ITracyBarPanelsProvider;
use Flame\Modules\Providers\ITracyPanelsProvider;
use Nette;
use Nette\Utils\Validators;


class ModulesExtension extends Nette\DI\CompilerExtension
{

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

	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		$builder = $this->getContainerBuilder();

		if ($builder->parameters['debugMode']) {
			$initialize = $class->methods['initialize'];

			foreach ($this->compiler->getExtensions() as $extension) {
				if ($extension instanceof ITracyBarPanelsProvider) {
					foreach ($extension->getTracyBarPanels() as $item) {
						$initialize->addBody($builder->formatPhp(
							'Nette\Diagnostics\Debugger::getBar()->addPanel(?);',
							Nette\DI\Compiler::filterArguments([is_string($item) ? new Nette\DI\Statement($item) : $item])
						));
					}
				}

				if ($extension instanceof ITracyPanelsProvider) {
					foreach ($extension->getTracyPanels() as $item) {
						$initialize->addBody($builder->formatPhp(
							'Nette\Diagnostics\Debugger::getBlueScreen()->addPanel(?);',
							Nette\DI\Compiler::filterArguments([$item])
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

			$latteFactory->addSetup($macro . '(?->getCompiler());', ['@self']);
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

					$latteFactory->addSetup('Flame\Modules\Template\Helper::register($service, ?)', [$provider]);

				} else {
					Validators::assert($name, 'string', 'name');
					Validators::assert($helper, 'string|array', 'helper');

					$latteFactory->addSetup('addFilter', [$name, $helper]);
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
				->addSetup('setMapping', [$mapping]);
		}
	}

	private function setupRouter(IRouterProvider $extension)
	{
		$routes = $extension->getRoutesDefinition();
		Validators::assert($routes, 'array', 'routes');

		$builder = $this->getContainerBuilder();
		$router = $builder->getDefinition('router');

		if (count($routes)) {
			foreach ($routes as &$service) {
				if ($service instanceof Nette\Application\Routers\Route
					|| $service instanceof Nette\Application\Routers\RouteList) {
					$service = $this->createRouteMock($service);

				} elseif (is_array($service) && count($service) >= 1) {
					$class = key($service);
					$service = new RouteMock($class, $service[$class]);

				} elseif (is_string($service)) {
					$service = new RouteMock($service);
				}

				Validators::assert($service, 'Flame\Modules\Application\Routers\IRouteMock');

				// In the future use this instead of RouterFactory
				//$router->addSetup('offsetSet', array(NULL, $service));
			}

			$router->addSetup('Flame\Modules\Application\RouterFactory::prependTo($service, ?)', [$routes]);
		}
	}

	/**
	 * @param mixed $route
	 * @return array|NetteRouteListMock|NetteRouteMock
	 */
	private function createRouteMock($route)
	{
		Validators::assert($route, 'Nette\Application\Routers\Route|Nette\Application\Routers\RouteList');

		if ($route instanceof Nette\Application\Routers\Route) {
			return new NetteRouteMock($route->getMask(), $route->getDefaults(), $route->getFlags());

		} else {
			$module = trim($route->getModule(), ':');
			$mock = new NetteRouteListMock($module);

			foreach ($route as $item) {
				$mock[] = $this->createRouteMock($item);
			}

			return $mock;
		}
	}

	private function setupErrorPresenter(IErrorPresenterProvider $extension)
	{
		$presenterName = $extension->getErrorPresenterName();
		Validators::assert($presenterName, 'string', 'presenterName');

		$builder = $this->getContainerBuilder();
		$builder->getDefinition('application')
			->addSetup('$errorPresenter', [$presenterName]);
	}


	/**
	 * @return Nette\DI\ServiceDefinition
	 */
	private function getLatteFactory()
	{
		$builder = $this->getContainerBuilder();
		return $builder->hasDefinition('nette.latteFactory')
			? $builder->getDefinition('nette.latteFactory')
			: $builder->getDefinition('nette.latte');
	}

}
