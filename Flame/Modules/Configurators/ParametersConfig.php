<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

class ParametersConfig extends Config implements IParametersConfig
{

	/** @var array  */
	private $parameters = array();

	/**
	 * @return mixed
	 */
	public function getConfiguration()
	{
		return $this->parameters;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function setParameter($name, $value)
	{
		$this->parameters[(string) $name] = $value;
		return $this;
	}
}