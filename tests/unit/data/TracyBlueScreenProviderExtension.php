<?php

/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
class TracyBlueScreenProviderExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\ITracyPanelsProvider
{

	/**
	 * Returns array of panel renderer callbacks
	 *
	 * @example https://gist.github.com/jsifalda/092e8f83175514feff21
	 * @param \Flame\Modules\Configurators\ITracyPanelsConfig &$tracyPanelsConfig
	 * @return array
	 */
	function setupTracyPanels(\Flame\Modules\Configurators\ITracyPanelsConfig &$tracyPanelsConfig)
	{
		$tracyPanelsConfig->addTracyPanel('BlueScreenPanel::test');
	}


}