<?php
/**
 * Class PresenterLayoutFactory
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 24.08.13
 */

namespace Flame\Modules\Application;

use Nette\Application\UI\Presenter;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;

/**
 * Class PresenterLayoutFactory
 *
 * @package Flame\Modules\Application
 */
trait PresenterLayoutFactory
{

	/** @var  string */
	private $layoutPath;

	/**
	 * @return array
	 * @throws \Nette\InvalidStateException
	 */
	public function formatLayoutTemplateFiles()
	{
		if(!$this instanceof Presenter) {
			throw new InvalidStateException('Trait ' . __TRAIT__  . ' cannot be used out of the Presenter');
		}

		$name = $this->getName();
		$presenter = substr($name, strrpos(':' . $name, ':'));
		$layout = $this->layout ? $this->layout : 'layout';
		$dir = $this->layoutPath;
		$list = array(
			"$dir/templates/$presenter/@$layout.latte",
			"$dir/templates/$presenter.@$layout.latte",
			"$dir/templates/$presenter/@$layout.phtml",
			"$dir/templates/$presenter.@$layout.phtml",
		);
		do {
			$list[] = "$dir/templates/@$layout.latte";
			$list[] = "$dir/templates/@$layout.phtml";
			$dir = dirname($dir);
		} while ($dir && ($name = substr($name, 0, strrpos($name, ':'))));

		return $list;
	}

	/**
	 * @param string $path
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function setLayoutPath($path)
	{
		$path = (string) $path;
		if(!is_dir($path)) {
			throw new InvalidArgumentException('Layout directory "' . $path . '" does not exist.');
		}

		$this->layoutPath = realpath($path);
		return $this;
	}

}