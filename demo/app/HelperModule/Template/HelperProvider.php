<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\HelperModule\Template;

use Flame\Modules\Template\IHelperProvider;
use Latte\Engine;

class HelperProvider implements IHelperProvider
{

	/**
	 * Provide custom helper registration
	 *
	 * @param Engine $engine
	 * @return void
	 */
	public function register(Engine $engine)
	{
		$engine->addFilter('helper1', function ($node) {
			echo 'helper1_' . $node;
		});
	}
}