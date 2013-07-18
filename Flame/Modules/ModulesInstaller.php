<?php
/**
 * Class ModulesInstaller
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 17.07.13
 */
namespace Flame\Modules;

use Flame\Modules\Config\Parser;
use Flame\Modules\Extension\IModuleExtension;
use Flame\Modules\Providers\IConfigProvider;
use Flame\Modules\DI\ConfiguratorHelper;
use Nette\DI\CompilerExtension;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Object;
use Nette\Utils\Neon;

class ModulesInstaller extends Object
{

	/** @var  Parser */
	private $parser;

	/** @var  ConfiguratorHelper */
	private $helper;

	/**
	 * @param ConfiguratorHelper $helper
	 * @param Parser $parser
	 */
	function __construct(ConfiguratorHelper $helper, Parser $parser)
	{
		$this->helper = $helper;
		$this->parser = $parser;
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

		$config = $this->getConfig($filePath);
		if(count($config) && isset($config['modules']) && count($modules = $config['modules'])) {
			foreach($modules as $name => $moduleClass) {
				$this->registerExtension($moduleClass, (is_string($name)) ? $name : null);
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

		$this->helper->registerExtension($extension, $name);
		$this->parseProviders($extension);

		if($callback !== null && is_callable($callback)) {
			call_user_func($callback, $this->helper->getConfigurator(), $extension, $name);
		}

		return $this;
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
	 * @param CompilerExtension $extension
	 */
	protected function parseProviders(CompilerExtension $extension)
	{
		if($extension instanceof IConfigProvider) {
			$this->parser->parseConfigProvider($extension);
		}
	}

	/**
	 * @param $filePath
	 * @return array|mixed
	 */
	private function getConfig($filePath)
	{
		$extension = pathinfo($filePath, PATHINFO_EXTENSION);
		switch ($extension) {
			case 'neon':
				$config = Neon::decode(file_get_contents($filePath));
				break;
			case 'php':
				$config = include_once($filePath);
				break;
			default:
				$config = array();
				break;
		}

		return $config;
	}

}