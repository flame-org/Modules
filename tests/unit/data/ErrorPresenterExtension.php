<?php

/**
 * @author Ondřej Záruba
 */
class ErrorPresenterExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\IErrorPresenterProvider
{

	/**
	 * Return name of error presenter
	 *
	 * @example https://gist.github.com/jsifalda/cd32009e6c5c956b5a10
	 *
	 * @param \Flame\Modules\Configurators\IErrorPresenterConfig &$errorPresenterConfig
	 * @return void
	 */
	public function setupErrorPresenter(\Flame\Modules\Configurators\IErrorPresenterConfig &$errorPresenterConfig)
	{
		$errorPresenterConfig->setErrorPresenter('Flame:Module:Error');
	}


}