<?php

/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
class TracyBlueScreenProviderExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\ITracyPanelsProvider
{

	/**
	 * Returns array of panel renderer callbacks
	 *
	 * @see http://doc.nette.org/en/2.1/configuring#toc-debugger
	 * @return array
	 */
	function getTracyPanels()
	{

		return array(
			'BlueScreenPanel::test'
		);
	}
}