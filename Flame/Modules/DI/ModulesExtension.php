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

	/** @var array  */
	private $routes = array();

	/**
	 * @return void
	 */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$this->setupPresenterFactory($builder);

		$presenterFactory = $builder->getDefinition('nette.presenterFactory');
		$latte = $builder->getDefinition('nette.latte');
		$template = $builder->getDefinition('nette.template');
		$application = $builder->getDefinition('application');

		$extensions = $this->compiler->getExtensions();
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

			if($extension instanceof ITemplateHelpersProvider) {
				$this->setupHelpers($template, $extension);
			}

			if($extension instanceof IErrorPresenterProvider){
				$this->setupErrorPresenter($application, $extension);
			}
		}


		if(count($this->routes)) {
			$builder->getDefinition('router')
				->addSetup('Flame\Modules\Application\RouterFactory::prependTo($service, ?)', array($this->routes));
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
	 * @param Nette\DI\ContainerBuilder $builder
	 * @return void
	 */
	protected function setupPresenterFactory(Nette\DI\ContainerBuilder &$builder)
	{
		if(version_compare(Nette\Framework::VERSION, '2.1-dev', '<')) {
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
	 * @return void
	 */
	private function setupMacros(Nette\DI\ServiceDefinition $latte, ILatteMacrosProvider $extension)
	{
		$macros = $extension->getLatteMacros();
		Nette\Utils\Validators::assert($macros, 'array');

		if(count($macros)) {
			foreach ($macros as $macro) {
				if (strpos($macro, '::') === FALSE && class_exists($macro)) {
					$macro .= '::install';

				} else {
					Nette\Utils\Validators::assert($macro, 'callable');
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
	private function setupHelpers(Nette\DI\ServiceDefinition $template, ITemplateHelpersProvider $extension)
	{
		$helpers = $extension->getHelpersConfiguration();
		Nette\Utils\Validators::assert($helpers, 'array');

		if(count($helpers)) {
			$builder = $this->getContainerBuilder();
			foreach ($helpers as $name => $helper) {
				if(is_string($helper) && !is_string($name)) {
					$provider = $builder->addDefinition($this->prefix('helperProvider' . $name))
						->setClass($helper);

					$template->addSetup('Flame\Modules\Template\Helper::register($service, ?)', $provider);
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
	 * @return void
	 */
	private function setupPresenterMapping(Nette\DI\ServiceDefinition $presenterFactory, IPresenterMappingProvider $extension)
	{
		$mapping = $extension->getPresenterMapping();
		Nette\Utils\Validators::assert($mapping, 'array');

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
		Nette\Utils\Validators::assert($routes, 'array');

		if (count($routes)) {
			$this->routes = array_merge($this->routes, $routes);
		}
	}

	/**
	 * @param Nette\DI\ServiceDefinition $application
	 * @param IErrorPresenterProvider $extension
	 */
	private function setupErrorPresenter(Nette\DI\ServiceDefinition $application, IErrorPresenterProvider $extension)
	{
		$presenterName = $extension->getErrorPresenterName();
		Nette\Utils\Validators::assert($presenterName, 'string');

		$application->addSetup('$errorPresenter', $presenterName);
	}
}