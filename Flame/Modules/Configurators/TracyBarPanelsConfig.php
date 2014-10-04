<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

class TracyBarPanelsConfig extends Config implements ITracyBarPanelsConfig
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
	public function addTracyBarPanel($name)
	{
		$this->panels[] = $name;
		return $this;
	}
}