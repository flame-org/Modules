<?php
/**
 * Class Helper
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 23.08.13
 */
namespace Flame\Modules\Template;

use Nette\InvalidStateException;
use Nette\Object;
use Nette\Templating\Template;

class Helper extends Object
{

	/**
	 * @param Template $template
	 * @param $helper
	 * @throws \Nette\InvalidStateException
	 * @return void
	 */
	public static function register(Template $template, $helper)
	{
		if(!$helper instanceof IHelperProvider) {
			throw new InvalidStateException('Helper provider must be instance of "Flame\Modules\Template\IHelperProvider"');
		}

		$helper->register($template);
	}

}