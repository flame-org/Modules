<?php

use Latte\Engine;

/**
 * @author Ondřej Záruba
 */
class TestHelper2 implements \Flame\Modules\Template\IHelperProvider
{
	/**
	 * Provide custom helper registration
	 *
	 * @param Engine $engine
	 * @return void
	 */
	public function register(Engine $engine)
	{
		$engine->addFilter('flamehelpertype2', array($this, 'process'));
	}

	public function process($s)
	{
		return 'output2';
	}
}