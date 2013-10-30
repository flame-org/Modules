<?php
/**
 * Class NamedExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 17.07.13
 */
namespace Flame\Modules\Extension;

use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\Utils\Strings;

abstract class NamedExtension extends CompilerExtension implements INamedExtension
{

	/** @var  string */
	public static $shortName;

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
		if(static::$shortName === null) {
			$name = self::getReflection()->getShortName();
			$name = str_replace('Extension', '', $name);
			static::$shortName = Strings::lower($name);
		}

		return static::$shortName;
	}

}