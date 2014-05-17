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
	 * @see http://doc.nette.org/en/2.1/configuring#toc-debugger
	 * @example https://gist.github.com/jsifalda/8e83323136620e1a0886
	 * @return array
	 */
	function getTracyBarPanels();
}