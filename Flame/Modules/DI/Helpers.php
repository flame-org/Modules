<?php
/**
 * Class Helpers
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 19.08.13
 */
namespace Flame\Modules\DI;

use Nette;

final class Helpers
{

	/**
	 * Generates list of properties with annotation @inject.
	 *
	 * @param Nette\Reflection\ClassType $class
	 * @return array
	 * @throws \Nette\InvalidStateException
	 */
	public static function getInjectProperties(Nette\Reflection\ClassType $class)
	{
		$res = array();
		foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
			$type = $property->getAnnotation('var');
			if (!$property->getAnnotation('inject')) {
				continue;

			} elseif (!$type) {
				throw new Nette\InvalidStateException("Property $property has not @var annotation.");

			} elseif (!class_exists($type) && !interface_exists($type)) {
				if ($type[0] !== '\\') {
					$type = $property->getDeclaringClass()->getNamespaceName() . '\\' . $type;
				}
				if (!class_exists($type) && !interface_exists($type)) {
					throw new Nette\InvalidStateException("Please use a fully qualified name of class/interface in @var annotation at $property property. Class '$type' cannot be found.");
				}
			}
			$res[$property->getName()] = $type;
		}
		return $res;
	}

}