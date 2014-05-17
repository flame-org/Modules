<?php
/**
 * Class ITemplateHelperProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 22.08.13
 */

namespace Flame\Modules\Providers;


interface ITemplateHelpersProvider
{

	/**
	 * Return list of helpers definitions or providers
	 *
	 * @example https://gist.github.com/jsifalda/7f570f94974b62163117
	 * @return array
	 */
	public function getHelpersConfiguration();
}