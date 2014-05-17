<?php

/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
class TracyBarProviderExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\ITracyBarPanelsProvider
{

	/**
	 * Returns array of classes or services that will be configured to bar panels
	 *
	 * @see http://doc.nette.org/en/2.1/configuring#toc-debugger
	 * @return array
	 */
	function getTracyBarPanels()
	{
		return array(
			'BarPanel'
		);
	}
} 