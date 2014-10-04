<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

class TracyPanelsConfig extends Config implements ITracyPanelsConfig
{

	/** @var array  */
	private $panels = array();

	/**
	 * @return mixed
	 */
	public function getConfiguration()
	{
		return $this->panels;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function addTracyPanel($name)
	{
		$this->panels[] = $name;
		return $this;
	}
}