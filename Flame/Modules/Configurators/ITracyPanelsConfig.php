<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

interface ITracyPanelsConfig 
{

	/**
	 * @param string $name
	 * @return $this
	 */
	public function addTracyPanel($name);
} 