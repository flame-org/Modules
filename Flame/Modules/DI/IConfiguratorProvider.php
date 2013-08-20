<?php
/**
 * Class IConfiguratorProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 20.08.13
 */

namespace Flame\Modules\DI;


interface IConfiguratorProvider
{

	/**
	 * @return \Nette\Configurator
	 */
	public function getConfigurator();
}