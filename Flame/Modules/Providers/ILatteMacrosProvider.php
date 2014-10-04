<?php
/**
 * Class ILatteMacrosProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 25.07.13
 */
namespace Flame\Modules\Providers;

use Flame\Modules\Configurators\ILatteMacrosConfig;

interface ILatteMacrosProvider
{

	/**
	 * Setup names of latte macros classes
	 *
	 * @example https://gist.github.com/jsifalda/8e781e6fc3a04038f44a
	 *
	 * @param ILatteMacrosConfig &$macrosConfig
	 * @return void
	 */
	public function setupMacros(ILatteMacrosConfig &$macrosConfig);
} 