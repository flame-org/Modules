<?php
/**
 * Class IHelperProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 22.08.13
 */

namespace Flame\Modules\Template;


use Nette\Templating\Template;

interface IHelperProvider
{

	/**
	 * Provide custom helper registration
	 *
	 * @param Template $template
	 * @return void
	 */
	public function register(Template $template);
}