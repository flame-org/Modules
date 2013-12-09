<?php
/**
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 */
namespace Flame\Modules\Config;

use Nette\InvalidArgumentException;

class PhpFile implements IConfigFile {

	/** @var string */
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