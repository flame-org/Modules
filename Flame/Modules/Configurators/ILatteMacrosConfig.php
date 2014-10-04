<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

interface ILatteMacrosConfig 
{

	/**
	 * @param string $name
	 * @return $this
	 */
	public function addMacro($name);

} 