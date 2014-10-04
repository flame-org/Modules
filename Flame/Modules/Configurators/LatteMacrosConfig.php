<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

class LatteMacrosConfig extends Config implements ILatteMacrosConfig
{

	/** @var  array */
	private $macros = array();

	/**
	 * @param string $name
	 * @return $this
	 */
	public function addMacro($name)
	{
		$this->macros[] = (string) $name;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getConfiguration()
	{
		return $this->macros;
	}


}