<?php
/**
 * Class ITracyPanelsProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 10.09.13
 */

namespace Flame\Modules\Providers;

use Flame\Modules\Configurators\ITracyPanelsConfig;

interface ITracyPanelsProvider
{

	/**
	 * Setup Tracy (bluescreen) panels
	 *
	 * @example https://gist.github.com/jsifalda/092e8f83175514feff21
	 * @param ITracyPanelsConfig &$tracyPanelsConfig
	 *
	 * @return void
	 */
	function setupTracyPanels(ITracyPanelsConfig &$tracyPanelsConfig);
}