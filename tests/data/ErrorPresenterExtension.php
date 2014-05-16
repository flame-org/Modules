<?php

/**
 * @author Ondřej Záruba
 */
class ErrorPresenterExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\IErrorPresenterProvider
{

	/**
	 * Return name of error presenter
	 *
	 * @return string
	 */
	public function getErrorPresenterName()
	{
		return 'Flame:Module:Error';
	}
}