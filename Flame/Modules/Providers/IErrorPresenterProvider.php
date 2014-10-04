<?php
/**
 * Class IErrorPresenterNameProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 24.08.13
 */
namespace Flame\Modules\Providers;

use Flame\Modules\Configurators\IErrorPresenterConfig;

interface IErrorPresenterProvider
{

	/**
	 * Setup error presenter name
	 *
	 * @example https://gist.github.com/jsifalda/cd32009e6c5c956b5a10
	 *
	 * @param IErrorPresenterConfig &$errorPresenterConfig
	 * @return void
	 */
	public function setupErrorPresenter(IErrorPresenterConfig &$errorPresenterConfig);

}