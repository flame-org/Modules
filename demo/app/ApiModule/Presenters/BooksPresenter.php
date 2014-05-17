<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\ApiModule\Presenters;

use Flame\Rest\Application\UI\RestPresenter;

class BooksPresenter extends RestPresenter
{

	public function actionReadAll()
	{
		$this->resource->ss = '- App\ApiModule\DI\ApiExtension';
	}

} 