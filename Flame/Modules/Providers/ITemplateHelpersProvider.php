<?php
/**
 * Class ITemplateHelperProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 22.08.13
 */

namespace Flame\Modules\Providers;


use Flame\Modules\Configurators\ITemplateHelpersConfig;

interface ITemplateHelpersProvider
{

	/**
	 * Setup helpers definitions or providers which will add as filters into your app
	 *
	 * @example https://gist.github.com/jsifalda/7f570f94974b62163117
	 * @param ITemplateHelpersConfig &$templateHelpersConfig
	 *
	 * @return void
	 */
	public function setupHelpers(ITemplateHelpersConfig &$templateHelpersConfig);
}