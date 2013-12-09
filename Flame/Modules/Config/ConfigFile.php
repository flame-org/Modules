<?php

namespace Flame\Modules\Config;

use Nette\NotSupportedException;

/**
 *
 * Bridge for BACK COMPATIBILITY with old implements!
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class ConfigFile extends \Nette\Object implements IConfigFile
{

	/**
	 * List of config files
	 * @var array
	 */
	private $paths = array();


	/**
	 * @param $path
	 * @return $this
	 * @throws \Nette\InvalidStateException
	 */
	public function loadConfig($path)
	{
		$this->paths[] = $path;
		return $this;
	}


	/**
	 * @return array
	 */
	public function getPaths()
	{
		return $this->paths;
	}


	/**
	 * @return array|void
	 * @throws \Nette\NotSupportedException
	 */
	public function getConfig()
	{
		throw new NotSupportedException('Method has been removed, new implements already exists.');
	}


	/**
	 * @throws \Nette\NotSupportedException
	 */
	public function getConfigSection()
	{
		throw new NotSupportedException('Method has been removed, new implements already exists.');
	}


	/**
	 * @throws \Nette\NotSupportedException
	 */
	public function getSupportedTypes()
	{
		throw new NotSupportedException('Method has been removed, new implements already exists.');
	}

}