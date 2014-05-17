<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\ErrorModule\Presenters;

use Nette\Application\UI\Presenter;

class ThrowExceptionPresenter extends Presenter
{

	public function actionDefault()
	{
		$this->error('test', 500);
	}
} 