<?php

namespace Flame\Tests\Modules\Providers;

use Flame\Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Ondřej Záruba
 */
class LatteMacrosProviderTest extends TestCase
{
	public function testLatteMacrosProvider()
	{
		/** @var \Latte\Engine $engine */
		$engine = $this->getContext()->getService('nette.latte')->create();
		$compiler = $engine->getCompiler();
		$reflectionProperty = new \ReflectionProperty($compiler, 'macros');
		$reflectionProperty->setAccessible(true);
		$macros = $reflectionProperty->getValue($compiler);
		$reflectionProperty->setAccessible(false);
		Assert::true(array_key_exists('FlameTestMacro', $macros));
	}
}

\run(new LatteMacrosProviderTest(getContainer()));