<?php
/**
 * Class IHelperProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 22.08.13
 */

namespace Flame\Modules\Template;

use Latte\Engine;

interface IHelperProvider
{

	/**
	 * Provide custom helper registration
	 *
	 * @param Engine $engine
	 * @return void
	 */
	public function register(Engine $engine);
}