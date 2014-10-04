<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

interface ITemplateHelpersConfig 
{

	/**
	 * @param string $name
	 * @param mixed $service
	 * @return $this
	 */
	public function addHelper($name, $service);

	/**
	 * @param string $name
	 * @return $this
	 */
	public function addHelperClass($name);
} 