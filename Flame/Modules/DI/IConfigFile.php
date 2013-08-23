<?php
/**
 * Class IConfigFile
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 23.08.13
 */

namespace Flame\Modules\DI;


interface IConfigFile
{

	/**
	 * Load config content
	 *
	 * @param string $path
	 * @return $this
	 */
	public function loadConfig($path);

	/**
	 * Get loaded config content
	 *
	 * @return mixed
	 */
	public function getConfig();

	/**
	 * Get "modules" config section
	 *
	 * @return array
	 */
	public function getConfigSection();

	/**
	 * Return list of file extension types
	 *
	 * @return array
	 */
	public function getSupportedTypes();
}