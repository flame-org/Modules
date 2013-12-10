<?php
/**
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 * @author Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Config;

use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Object;

class PhpFile extends Object implements IConfigFile
{

	/** @var string */
	private $path;

	/**
	 * @param string $path
	 * @throws InvalidArgumentException
	 */
	public function __construct($path)
	{
		if (!file_exists($path)) {
			throw new InvalidArgumentException('Given config path "' . $path . '" does not exists.');
		}

		$this->path = $path;
	}

	/**
	 * @return array
	 * @throws \Nette\InvalidStateException
	 */
	public function getConfig()
	{
		$config = include($this->path);
		if(isset($config['modules'])) {
			return $config['modules'];
		}

		throw new InvalidStateException('Missing section "modules" in configuration file.');
	}
}