<?php
/**
 * Class Helper
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 23.08.13
 */
namespace Flame\Modules\Template;

use Latte\Engine;
use Nette\InvalidStateException;
use Nette\Object;

class Helper extends Object
{

	/**
	 * @param Engine $engine
	 * @param $helper
	 * @throws \Nette\InvalidStateException
	 * @return void
	 */
	public static function register(Engine $engine, $helper)
	{
		if(!$helper instanceof IHelperProvider) {
			throw new InvalidStateException('Helper provider must be instance of "Flame\Modules\Template\IHelperProvider"');
		}

		$helper->register($engine);
	}

}