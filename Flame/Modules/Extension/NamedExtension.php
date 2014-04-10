<?php
/**
 * Class NamedExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 17.07.13
 */
namespace Flame\Modules\Extension;

use Nette\DI\CompilerExtension;

abstract class NamedExtension extends CompilerExtension implements INamedExtension
{
	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getShortName();
	}

	/**
	 * @param $name
	 * @return string
	 */
	public function getConfigPath($name)
	{
		return dirname($this->getReflection()->getFileName()) . '/../config/' . (string) $name . '.neon';
	}

	/**
	 * @return string
	 */
	public static function getShortName()
	{
		$name = static::getReflection()->getShortName();
		$name = str_replace('Extension', '', $name);
		return lcfirst($name);
	}

}