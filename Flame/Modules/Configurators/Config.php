<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Configurators;

use Nette\Object;

abstract class Config extends Object
{

	/**
	 * @return mixed
	 */
	abstract public function getConfiguration();
} 