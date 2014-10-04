<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

interface IPresenterMappingConfig
{

	/**
	 * @param string $module
	 * @param string $namespace
	 * @return $this
	 */
	public function setMapping($module, $namespace);

} 