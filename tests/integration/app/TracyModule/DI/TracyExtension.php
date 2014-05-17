<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\TracyModule\DI;

use Flame\Modules\Providers\ITracyBarPanelsProvider;
use Flame\Modules\Providers\ITracyPanelsProvider;
use Nette\DI\CompilerExtension;

class TracyExtension extends CompilerExtension implements ITracyBarPanelsProvider, ITracyPanelsProvider
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
			'App\TracyModule\Panels\MyBarPanel'
		);
	}

	/**
	 * Returns array of panel renderer callbacks
	 *
	 * @see http://doc.nette.org/en/2.1/configuring#toc-debugger
	 * @return array
	 */
	function getTracyPanels()
	{
		return array(
			'App\TracyModule\Panels\MyBlueScreenPanel::test'
		);
	}
}