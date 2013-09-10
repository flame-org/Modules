<?php
/**
 * Class ConfiguratorHelper
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 18.07.13
 */
namespace Flame\Modules\DI;

use Nette\Configurator;
use Nette\DI\CompilerExtension;
use Flame\Modules\Extension\INamedExtension;
use Nette\InvalidStateException;
use Nette\Object;

class ConfiguratorHelper extends Object implements IConfiguratorProvider
{

	/** @var  \Nette\Configurator */
	private $configurator;

	/**
	 * @param Configurator $configurator
	 */
	function __construct(Configurator $configurator)
	{
		$this->configurator = $configurator;
	}

	/**
	 * @param CompilerExtension $extension
	 * @param null $name
	 * @return $this
	 * @throws \Nette\InvalidStateException
	 */
	public function registerExtension(CompilerExtension $extension, $name = null)
	{
		if($name === null) {
			if (!$extension instanceof INamedExtension) {
				throw new InvalidStateException('Please set module name, or implement Flame\Modules\INamedExtension');
			}

			$name = $extension->getName();
		}

		$this->configurator->onCompile[] = function ($config, $compiler) use ($extension, $name) {
			$compiler->addExtension($name, $extension);
		};

		return $this;
	}

	/**
	 * @param array $configsFiles
	 * @return $this
	 */
	public function addConfigs(array $configsFiles)
	{
		foreach ($configsFiles as $file) {
			$this->configurator->addConfig($file);
		}

		return $this;
	}

	/**
	 * @return Configurator
	 */
	public function getConfigurator()
	{
		return $this->configurator;
	}

}