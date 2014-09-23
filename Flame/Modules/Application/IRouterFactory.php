<?php

namespace Flame\Modules\Application;

use Nette;

interface IRouterFactory
{

	/**
	 * Provides custom module router
	 *
	 * @return Nette\Application\IRouter
	 */
	public function createRouter();

}
