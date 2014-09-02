<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\ErrorModule\DI;

use Flame\Modules\Providers\IErrorPresenterProvider;
use Nette\DI\CompilerExtension;

class ErrorExtension extends CompilerExtension implements IErrorPresenterProvider
{

	/**
	 * Return name of error presenter
	 *
	 * @return string
	 */
	public function getErrorPresenterName()
	{
		return 'Error:CustomError';
	}
}