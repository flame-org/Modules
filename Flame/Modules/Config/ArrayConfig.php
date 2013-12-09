<?php
/**
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 */
namespace Flame\Modules\Config;

class ArrayConfig implements IConfigFile {

	/** @var array */
	private $config;

	/**
	 * @param array $array
	 */
	public function __construct(array $array)
	{
		$this->config = $array;
	}

	/**
	 * @return array
	 */
	public function getConfig()
	{
		return $this->config;
	}

}