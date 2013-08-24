<?php
/**
 * Class ModulesInstaller
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 17.07.13
 */
namespace Flame\Modules;

use Flame\Modules\Config\IConfigFile;
use Flame\Modules\Config\ConfigFile;
use Flame\Modules\Extension\IDomainExtension;
use Flame\Modules\Providers\IConfigProvider;
use Flame\Modules\DI\ConfiguratorHelper;
use Nette\DI\CompilerExtension;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Object;
use Nette\Utils\Neon;
use Nette\Utils\Validators;

class ModulesInstaller extends Object
{

	/** @var \Flame\Modules\Config\IConfigFile  */
	private $configFile;

	/** @var \Flame\Modules\DI\ConfiguratorHelper  */
	private $helper;

	/** @var array  */
	private $defaultExtensions = array(
		'Flame\Modules\DI\ModulesExtension'
	);

	/**
	 * @param ConfiguratorHelper $helper
	 * @param IConfigFile $configFile
	 */
	function __construct(ConfiguratorHelper $helper, IConfigFile $configFile = null)
	{
		if($configFile === null) {
			$configFile = new ConfigFile;
		}

		$this->configFile = $configFile;
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

		$modules = $this->configFile->loadConfig($filePath)->getConfigSection();
		if(count($modules)) {
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
			throw new InvalidArgumentException('Extension must be class name (string) or instance of \Nette\DI\CompilerExtension');
		}

		if($extension instanceof IDomainExtension) {
			$extension->register($this->helper->getConfigurator());
		}else{
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
	 * @return null|object
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
	protected function setupConfigProvider(IConfigProvider &$extension)
	{
		$configs = $extension->getConfigFiles();
		Validators::assert($configs, 'array');
		$this->helper->addConfigs($configs);
	}

	/**
	 * @param CompilerExtension $extension
	 * @return void
	 */
	protected function parseProviders(CompilerExtension &$extension)
	{
		if($extension instanceof IConfigProvider) {
			$this->setupConfigProvider($extension);
		}
	}
}