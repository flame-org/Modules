<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

interface IErrorPresenterConfig 
{

	/**
	 * @param string $presenterName
	 * @return $this
	 */
	public function setErrorPresenter($presenterName);
} 