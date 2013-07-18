<?php
/**
 * Class ModulesInstaller
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 17.07.13
 */
namespace Flame\Modules;

use Flame\Modules\Extension\IModuleExtension;
use Flame\Modules\Providers\IConfigProvider;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Config\Adapters\NeonAdapter;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Object;
use Nette\Utils\Neon;
use Nette\Utils\Validators;

class ModulesInstaller extends Object
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
	 * @param $filePath
	 * @return $this
	 * @throws \Nette\InvalidArgumentException
	 */
	public function addConfig($filePath)
	{

		if(!file_exists((string) $filePath)) {
			throw new InvalidArgumentException('Given config path does not exist');
		}

		$config = Neon::decode(file_get_contents($filePath));
		if(count($config) && isset($config['modules']) && count($modules = $config['modules'])) {
			foreach($modules as $name => $moduleClass) {
				$this->registerExtension($moduleClass, $name ? $name : null);
			}
		}

		return $this;
	}

	/**
	 * @param \Nette\DI\CompilerExtension|string $extension
	 * @param null $name
	 * @param null $callback
	 * @return $this
	 * @throws \Nette\InvalidStateException
	 * @throws \Nette\InvalidArgumentException
	 */
	public function registerExtension($extension, $name = null, $callback  = null)
	{
		if(is_string($extension)) {
			$extension = $this->invokeExtension($extension);
		}

		if (!$extension instanceof CompilerExtension) {
			throw new InvalidArgumentException('Extension must be name (string) or instance of \Nette\DI\CompilerExtension');
		}

		if($name === null) {
			if (!$extension instanceof IModuleExtension) {
				throw new InvalidStateException('Please set module name, or implement Flame\Modules\IModuleExtension');
			}

			$name = $extension->getName();
		}

		$this->register($extension, $name);

		if($extension instanceof IConfigProvider) {
			$this->parseConfigProvider($extension);
		}

		if($callback !== null && is_callable($callback)) {
			call_user_func($callback, $this->configurator, $extension, $name);
		}

		return $this;
	}

	/**
	 * @param CompilerExtension $extension
	 * @param $name
	 */
	protected function register(CompilerExtension $extension, $name)
	{
		$this->configurator->onCompile[] = function (Configurator $config, Compiler $compiler) use ($extension, $name) {
			$compiler->addExtension($name, $extension);
		};
	}

	/**
	 * @param $class
	 * @return mixed
	 * @throws \Nette\InvalidStateException
	 */
	protected function invokeExtension($class)
	{
		if(is_string($class)){
			return new $class;
		}

		throw new InvalidStateException('Definition of extension must be valid class (string). ' . gettype($class) . ' given.');
	}

	/**
	 * @param IConfigProvider $extension
	 */
	protected function parseConfigProvider(IConfigProvider $extension)
	{
		$files = $extension->getConfigFiles();
		if(count($files)) {
			foreach ($files as $file) {
				$this->configurator->addConfig($file);
			}
		}
	}

}