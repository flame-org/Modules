<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\TracyModule\Panels;

use Tracy\IBarPanel;

class MyBarPanel implements IBarPanel
{

	/**
	 * Renders HTML code for custom tab.
	 *
	 * @return string
	 */
	function getTab()
	{
		return '<div>aaa</div>';
	}

	/**
	 * Renders HTML code for custom panel.
	 *
	 * @return string
	 */
	function getPanel()
	{
		return '<div>bbb</div>';
	}
}