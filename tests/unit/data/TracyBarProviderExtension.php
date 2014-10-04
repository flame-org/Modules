<?php

/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
class TracyBarProviderExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\ITracyBarPanelsProvider
{

	/**
	 * Returns array of classes or services that will be configured to bar panels
	 *
	 * @example https://gist.github.com/jsifalda/8e83323136620e1a0886
	 * @param \Flame\Modules\Configurators\ITracyBarPanelsConfig &$tracyBarPanelsConfig
	 * @return array
	 */
	function setupTracyBarPanels(\Flame\Modules\Configurators\ITracyBarPanelsConfig &$tracyBarPanelsConfig)
	{
		$tracyBarPanelsConfig->addTracyBarPanel('BarPanel');
	}


} 