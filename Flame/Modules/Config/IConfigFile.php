<?php
/**
 * Class IConfigFile
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 23.08.13
 */

namespace Flame\Modules\Config;

interface IConfigFile
{

	/**
	 * Get loaded config content
	 *
	 * @return array
	 */
	public function getConfig();
}