<?php

namespace Flame\Modules\Config;

use Nette\InvalidArgumentException;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class PhpFile implements IConfigFile {


	/**
	 * @var string
	 */
	private $path;


	/**
	 * @param string $path
	 * @throws InvalidArgumentException
	 */
	public function __construct($path)
	{
		if (!file_exists($path))
			throw new InvalidArgumentException('Given config path "' . $path . '" does not exists.');

		$this->path = $path;
	}


	/**
	 * @return array
	 */
	public function getConfig()
	{
		return include($this->path);
	}


}