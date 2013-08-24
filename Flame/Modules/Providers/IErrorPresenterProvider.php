<?php
/**
 * Class IErrorPresenterNameProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 24.08.13
 */
namespace Flame\Modules\Providers;

interface IErrorPresenterProvider
{

	/**
	 * Return name of error presenter
	 *
	 * @return string
	 */
	public function getErrorPresenterName();

}