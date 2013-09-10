<?php
/**
 * Class ITracyBarPanelsProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 10.09.13
 */
namespace Flame\Modules\Providers;

interface ITracyBarPanelsProvider
{
	/**
	 * Returns array of classes or services that will be configured to bar panels
	 *
	 * @see http://doc.nette.org/cs/configuring#toc-debugger
	 * @return array
	 */
	function getTracyBarPanels();
}