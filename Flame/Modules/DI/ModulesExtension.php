<?php
/**
 * Class ModulesExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 20.07.13
 */
namespace Flame\Modules\DI;

use Flame\Modules\Extension\NamedExtension;
use Flame\Modules\Providers\IErrorPresenterProvider;
use Flame\Modules\Providers\ILatteMacrosProvider;
use Flame\Modules\Providers\IRouterProvider;
use Flame\Modules\Providers\IPresenterMappingProvider;
use Flame\Modules\Providers\ITemplateHelpersProvider;
use Flame\Modules\Providers\ITracyBarPanelsProvider;
use Flame\Modules\Providers\ITracyPanelsProvider;
use Nette;

class ModulesExtension extends NamedExtension
{

	/**
	 * @return void
	 */
	public function loadConfiguration()
	{
		$this->setupPresenterFactory();
		$builder = $this->getContainerBuilder();

		$presenterFactory = $builder->getDefinition('nette.presenterFactory');
		$latte = $builder->getDefinition('nette.latte');
		$template = $builder->getDefinition('nette.template');
		$application = $builder->getDefinition('application');
		$router = $builder->getDefinition('router');

		$extensions = $this->compiler->getExtensions();
		foreach ($extensions as $extension) {
			if ($extension instanceof IPresenterMappingProvider) {
				$this->setupPresenterMapping($presenterFactory, $extension);
			}

			if ($extension instanceof IRouterProvider) {
				$this->setupRouter($router, $extension);
			}

			if($extension instanceof ILatteMacrosProvider) {
				$this->setupMacros($latte, $extension);
			}

			if($extension instanceof ITemplateHelpersProvider) {
				$this->setupHelpers($template, $extension);
			}

			if($extension instanceof IErrorPresenterProvider){
				$this->setupErrorPresenter($application, $extension);
			}
		}
	}

	/**
	 * @param Nette\PhpGenerator\ClassType $class
	 * @return void
	 */
	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		$container = $this->getContainerBuilder();

		if ($container->parameters['debugMode']) {
			$initialize = $class->methods['initialize'];

			foreach ($this->compiler->getExtensions() as $extension) {
				if ($extension instanceof ITracyBarPanelsProvider) {
					foreach ($extension->getTracyBarPanels() as $item) {
						$initialize->addBody($container->formatPhp(
							'Nette\Diagnostics\Debugger::getBar()->addPanel(?);',
							Nette\DI\Compiler::filterArguments(array(is_string($item) ? new Nette\DI\Statement($item) : $item))
						));
					}
				}

				if ($extension instanceof ITracyPanelsProvider) {
					foreach ($extension->getTracyPanels() as $item) {
						$initialize->addBody($container->formatPhp(
							'Nette\Diagnostics\Debugger::getBlueScreen()->addPanel(?);',
							Nette\DI\Compiler::filterArguments(array($item))
						));
					}
				}
			}
		}
	}

	/**
	 * @return void
	 */
	protected function setupPresenterFactory()
	{
		if(version_compare(Nette\Framework::VERSION, '2.1-dev', '<')) {
			$builder = $this->getContainerBuilder();
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
	 * @param Nette\DI\ServiceDefinition $latte
	 * @param ILatteMacrosProvider $extension
	 * @throws \Nette\InvalidStateException
	 */
	private function setupMacros(Nette\DI\ServiceDefinition &$latte, ILatteMacrosProvider &$extension)
	{
		$macros = $extension->getLatteMacros();
		if(!is_array($macros)) {
			throw new Nette\InvalidStateException('Definition of Latte macros must be in array.');
		}

		if(count($macros)) {
			foreach ($macros as $macro) {
				if (strpos($macro, '::') === FALSE && class_exists($macro)) {
					$macro .= '::install';
				} else {
					if(!is_callable($macro)) {
						throw new Nette\InvalidStateException('Macro must be callable.');
					}
				}

				$latte->addSetup($macro . '(?->compiler)', array('@self'));
			}
		}
	}

	/**
	 * @param Nette\DI\ServiceDefinition $template
	 * @param ITemplateHelpersProvider $extension
	 * @throws \Nette\InvalidStateException
	 */
	private function setupHelpers(Nette\DI\ServiceDefinition &$template, ITemplateHelpersProvider &$extension)
	{
		$helpers = $extension->getHelpersConfiguration();
		if (!is_array($helpers)) {
			throw new Nette\InvalidStateException('Definition of Latte helpers must be in array.');
		}

		if(count($helpers)) {
			$builder = $this->getContainerBuilder();
			foreach ($helpers as $name => $helper) {
				if(is_string($helper) && !is_string($name)) {
					$provider = $builder->addDefinition($this->prefix('helperProvider' . $name))
						->setClass($helper);

					$template->addSetup('Flame\Modules\Template\Helper::register($service, ?)', array($provider));
				}else{
					if(!is_string($name)) {
						throw new Nette\InvalidStateException('Template helper\'s name must be specified, "' . $name . '" given!');
					}

					if(!is_array($helper) && !is_string($helper)) {
						throw new Nette\InvalidStateException('Template helper\'s definition must be array or string, "' . gettype($helper) . '" given');
					}

					$template->addSetup('registerHelper', array($name, $helper));
				}
			}
		}
	}

	/**
	 * @param Nette\DI\ServiceDefinition $presenterFactory
	 * @param IPresenterMappingProvider $extension
	 * @throws \Nette\InvalidStateException
	 */
	private function setupPresenterMapping(Nette\DI\ServiceDefinition &$presenterFactory, IPresenterMappingProvider &$extension)
	{
		$mapping = $extension->getPresenterMapping();
		if(!is_array($mapping)) {
			throw new Nette\InvalidStateException('Presenter mapping must be in array.');
		}

		if (count($mapping)) {
			$presenterFactory->addSetup('setMapping', array($mapping));
		}
	}

	/**
	 * @param Nette\DI\ServiceDefinition $router
	 * @param IRouterProvider $extension
	 * @throws \Nette\InvalidStateException
	 */
	private function setupRouter(Nette\DI\ServiceDefinition &$router, IRouterProvider &$extension)
	{
		$routes = $extension->getRoutesDefinition();
		if(!is_array($routes)) {
			throw new Nette\InvalidStateException('Routes definition must be in array.');
		}

		if (count($routes)) {
			$router->addSetup('Flame\Modules\Application\RouterFactory::prependTo($service, ?)', array($routes));
		}
	}

	/**
	 * @param Nette\DI\ServiceDefinition $application
	 * @param IErrorPresenterProvider $extension
	 * @throws \Nette\InvalidStateException
	 */
	private function setupErrorPresenter(Nette\DI\ServiceDefinition &$application, IErrorPresenterProvider &$extension)
	{
		$presenterName = $extension->getErrorPresenterName();
		if(!is_string($presenterName)) {
			throw new Nette\InvalidStateException('Presenter name must be string.');
		}

		$application->addSetup('$errorPresenter', array($presenterName));
	}
}