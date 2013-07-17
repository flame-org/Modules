<?php
/**
 * Class ModulesExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 17.07.13
 */
namespace Flame\Modules\DI;

use Flame\Modules\IModuleExtension;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\InvalidStateException;
use Nette\Utils\Validators;

class ModulesExtension extends CompilerExtension
{

	const NAME = 'modules';

	/** @var array  */
	public $defaults = array(
		'packages' => array()
	);

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);

		Validators::isList($config);
		Validators::assertField($config, 'packages', 'array');

		if(count($packages = $config['packages'])){
			foreach($packages as $name => $extension) {
				$extension = $this->getValidExtension($extension, $name);

				if(!is_string($name)) {
					$name = $extension->getName();
				}

				$this->compiler->addExtension($name, $extension);
			}
		}
	}

	/**
	 * @param $extensionClassName
	 * @param $name
	 * @return mixed
	 * @throws \Nette\InvalidStateException
	 */
	protected function getValidExtension($extensionClassName, $name)
	{
		$extension = $this->invokeExtension($extensionClassName);

		if($extension instanceof CompilerExtension == false) {
			throw new InvalidStateException('Extension "' . $name . '" must be instance of Nette\DI\CompilerExtension');
		}

		if ($extension instanceof IModuleExtension == false) {
			throw new InvalidStateException('Extension "' . $name . '" must implement Flame\Modules\DI\IModuleExtension');
		}

		return $extension;
	}

	/**
	 * @param $class
	 * @return mixed
	 * @throws \Nette\InvalidStateException
	 */
	protected function invokeExtension($class)
	{
		$builder = $this->getContainerBuilder();
		if(is_object($class)){
			$ref = new \ReflectionClass($class->value);
			return $ref->newInstance(property_exists($class, 'attributes') ? $builder->expand($class->attributes) : array());
		}elseif(is_string($class)){
			return new $class;
		}

		throw new InvalidStateException('Definition of extension must be valid class (string or object). ' . gettype($class) . ' given.');
	}

	/**
	 * @param Configurator $configurator
	 */
	public static function register(Configurator $configurator)
	{
		$configurator->onCompile[] = function (Configurator $config, Compiler $compiler) {
			$compiler->addExtension(self::NAME, new ModulesExtension());
		};
	}

}