<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

class TemplateHelpersConfig extends Config implements ITemplateHelpersConfig
{

	/** @var array  */
	private $helpers = array();

	/**
	 * @return mixed
	 */
	public function getConfiguration()
	{
		return $this->helpers;
	}

	/**
	 * @param string $name
	 * @param mixed $service
	 * @return $this
	 */
	public function addHelper($name, $service)
	{
		$this->helpers[(string) $name] = $service;
		return $this;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function addHelperClass($name)
	{
		$this->helpers[] = (string) $name;
	}


}