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
	 * Get array of latte macros classes
	 *
	 * @return array
	 */
	public function getLatteMacros();
} 