<?php
/**
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 * @author Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Config;

use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Object;

class PhpConfigFile extends Object implements IConfigFile
{

	/** @var string */
	private $path;

	/** @var string */
	private $section;

	/**
	 * @param string $path
	 * @param string $section
	 * @throws InvalidArgumentException
	 */
	public function __construct($path, $section = 'modules')
	{
		if (!file_exists($path)) {
			throw new InvalidArgumentException('Given config path "' . $path . '" does not exists.');
		}

		$this->path = $path;
		$this->section = (string) $section;
	}

	/**
	 * @return array
	 * @throws \Nette\InvalidStateException
	 */
	public function getConfig()
	{
		$config = include($this->path);
		if($this->section !== null) {
			if(isset($config[$this->section])) {
				return $config[$this->section];
			}

			throw new InvalidStateException('Missing section "' . $this->section . '" in configuration file.');
		}


		return $config;
	}
}