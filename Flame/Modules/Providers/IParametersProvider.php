<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Providers;

interface IParametersProvider
{

	/**
	 * Return array of parameters,
	 * which you want to add into DIC
	 *
	 * @example return array('images' => '%wwwDir%/path/to/folder/with/images');
	 * @return array
	 */
	public function getParameters();
} 