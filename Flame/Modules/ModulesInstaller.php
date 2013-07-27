<?php
/**
 * Class ModulesInstaller
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 17.07.13
 */
namespace Flame\Modules;

use Flame\Modules\Extension\IDomainExtension;
use Flame\Modules\Extension\INamedExtension;
use Flame\Modules\Providers\IConfigProvider;
use Flame\Modules\DI\ConfiguratorHelper;
use Nette\DI\CompilerExtension;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Object;
use Nette\Utils\Neon;

class ModulesInstaller extends Object
{

	/** @var  ConfiguratorHelper */
	private $helper;

	/** @var array  */
	private $defaultExtensions = array(
		'Flame\Modules\DI\ModulesExtension'
	);

	/**
	 * @param ConfiguratorHelper $helper
	 */
	function __construct(ConfiguratorHelper $helper)
	{
		$this->helper = $helper;
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
	 * @param $extension
	 * @param null $name
	 * @return $this
	 * @throws \Nette\InvalidStateException
	 * @throws \Nette\InvalidArgumentException
	 */
	public function registerExtension($extension, $name = null)
	{
		if(is_string($extension)) {
			$extension = $this->invokeExtension($extension);
		}

		if (!$extension instanceof CompilerExtension) {
			throw new InvalidArgumentException('Extension must be name (string) or instance of \Nette\DI\CompilerExtension');
		}

		if($extension instanceof IDomainExtension) {
			$extension->register($this->helper->getConfigurator());
		}else{
			if($name === null) {
				if (!$extension instanceof INamedExtension) {
					throw new InvalidStateException('Please set module name, or implement Flame\Modules\INamedExtension');
				}

				$name = $extension->getName();
			}

			$this->helper->registerExtension($extension, $name);
		}

		$this->parseProviders($extension);
		return $this;
	}

	/**
	 * @return $this
	 */
	public function register()
	{
		foreach($this->defaultExtensions as $extension) {
			$this->registerExtension($extension);
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
	 * @return void
	 */
	protected function parseProviders(CompilerExtension &$extension)
	{
		if($extension instanceof IConfigProvider) {
			$this->helper->addConfigs($extension->getConfigFiles());
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