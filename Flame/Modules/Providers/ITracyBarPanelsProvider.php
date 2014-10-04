<?php
/**
 * Class ITracyBarPanelsProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 10.09.13
 */
namespace Flame\Modules\Providers;

use Flame\Modules\Configurators\ITracyBarPanelsConfig;

interface ITracyBarPanelsProvider
{
	/**
	 * Setup classes or services which will be configured to bar panels
	 *
	 * @example https://gist.github.com/jsifalda/8e83323136620e1a0886
	 * @param ITracyBarPanelsConfig &$tracyBarPanelsConfig
	 *
	 * @return void
	 */
	function setupTracyBarPanels(ITracyBarPanelsConfig &$tracyBarPanelsConfig);
}