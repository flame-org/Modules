<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

interface ITracyBarPanelsConfig 
{

	/**
	 * @param string $name
	 * @return $this
	 */
	public function addTracyBarPanel($name);
} 