<?php
/**
 * Class ModulesInstaller
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 17.07.13
 */
namespace Flame\Modules;

use Flame\Modules\Config\ConfigFile;
use Flame\Modules\Config\IConfigFile;
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

	/** @var \Flame\Modules\DI\ConfiguratorHelper  */
	private $helper;

	/** @var array  */
	private $defaultExtensions = array(
		'Flame\Modules\DI\ModulesExtension'
	);

	/** @var array */
	private $configFileReaders = array(
		'php'	=> 'Flame\Modules\Config\PhpFile',
		'neon'	=> 'Flame\Modules\Config\NeonFile'
	);

	/**
	 * @param ConfiguratorHelper $helper
	 * @param IConfigFile $configFile
	 */
	function __construct(ConfiguratorHelper $helper, IConfigFile $configFile = NULL)
	{
		$this->helper = $helper;

		// Support for back compatibility
		if ($configFile instanceof ConfigFile) {
			foreach ($configFile->getPaths() as $path)
				$this->addConfig($path);
		} else if ($configFile !== null) {
			$this->addConfig($configFile);
		}
	}

	/**
	 * @param IConfigFile|array|string $config
	 * @return $this
	 * @throws \Nette\InvalidArgumentException
	 */
	public function addConfig($config)
	{
		if (!$config instanceof IConfigFile) {
			if (is_array($config)) {
				$config = new \Flame\Modules\Config\ArrayConfig($config);
			}
			elseif (is_string($config)) {

				if (!file_exists($config))
					throw new InvalidArgumentException('Given config path "' . $config . '" does not exists.');

				$type = $this->getType($config);
				if (isset($this->configFileReaders[$type])) {
					$class = $this->configFileReaders[$type];
					$config = new $class($config);
				} else
					throw new InvalidArgumentException('Unsupported config type "' . $type . '".');

			}
			else
				throw new InvalidArgumentException('Config must be instance of IConfigFile
						OR path to the config file OR associative array of modules.');
		}

		$modules = $config->getConfig();
		if (!is_array($modules))
			throw new InvalidArgumentException('Config must return array.');

		foreach($modules as $name => $moduleClass) {
			$this->registerExtension($moduleClass, (is_string($name)) ? $name : null);
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


	/**
	 * @param string $filePath
	 * @return string
	 */
	protected  function getType($filePath)
	{
		return pathinfo($filePath, PATHINFO_EXTENSION);
	}


}