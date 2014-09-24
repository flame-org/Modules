<?php
/**
 * Class ILatteMacrosProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 25.07.13
 */
namespace Flame\Modules\Providers;

interface ILatteMacrosProvider
{

	/**
	 * Get array with names of latte macros classes
	 *
	 * @example https://gist.github.com/jsifalda/8e781e6fc3a04038f44a
	 * @return array
	 */
	public function getLatteMacros();
} 