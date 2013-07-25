<?php
/**
 * Class IExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 17.07.13
 */

namespace Flame\Modules\Extension;

interface INamedExtension
{

	/**
	 * @return string
	 */
	public function getName();

}