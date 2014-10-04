<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

class ErrorPresenterConfig extends Config implements IErrorPresenterConfig
{

	/** @var null  */
	private $presenterName;

	/**
	 * @return mixed
	 */
	public function getConfiguration()
	{
		return $this->presenterName;
	}

	/**
	 * @param string $presenterName
	 * @return $this
	 */
	public function setErrorPresenter($presenterName)
	{
		$this->presenterName = (string) $presenterName;
		return $this;
	}
}