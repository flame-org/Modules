<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

class PresenterMappingConfig extends Config implements IPresenterMappingConfig
{

	/** @var array  */
	private $mapping = array();

	/**
	 * @return mixed
	 */
	public function getConfiguration()
	{
		return $this->mapping;
	}

	/**
	 * @param string $module
	 * @param string $namespace
	 * @return $this
	 */
	public function setMapping($module, $namespace)
	{
		$this->mapping[(string) $module] = (string) $namespace;
		return $this;
	}
}