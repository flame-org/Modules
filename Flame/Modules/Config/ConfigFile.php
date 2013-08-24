<?php
/**
 * Class ConfigFile
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 23.08.13
 */
namespace Flame\Modules\Config;

use Nette\InvalidStateException;
use Nette\Object;
use Nette\Utils\Neon;

class ConfigFile extends Object implements IConfigFile
{

	const TYPE_NEON = 'neon';
	const TYPE_PHP = 'php';

	/** @var array  */
	private $config = array();

	/** @var  array */
	private $supportedTypes;

	function __construct()
	{
		$this->supportedTypes = array(self::TYPE_NEON, self::TYPE_PHP);
	}

	/**
	 * @return array
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * @param $path
	 * @return $this
	 * @throws \Nette\InvalidStateException
	 */
	public function loadConfig($path)
	{
		$type = $this->getType($path);
		if(!in_array($type, $this->supportedTypes)) {
			throw new InvalidStateException(
				'Unsupported file extension. Allowed are ' . implode(', ', $this->supportedTypes));
		}

		switch ($type) {
			case self::TYPE_NEON:
				$this->config = Neon::decode(file_get_contents($path));
				break;
			case self::TYPE_PHP:
				$this->config = include($path);
				break;
			default:
				$this->config = array();
				break;
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getConfigSection()
	{
		$config = $this->getConfig();
		return (isset($config['modules'])) ? $config['modules'] : array();
	}

	/**
	 * Return list of file extension types
	 *
	 * @return array
	 */
	public function getSupportedTypes()
	{
		return $this->supportedTypes;
	}

	/**
	 * @param $filePath
	 * @return mixed
	 */
	protected  function getType($filePath)
	{
		return pathinfo($filePath, PATHINFO_EXTENSION);
	}
}