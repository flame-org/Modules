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
	 * @example https://gist.github.com/jsifalda/cd32009e6c5c956b5a10
	 * @return string
	 */
	public function getErrorPresenterName();

}