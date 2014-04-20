<?php

namespace Flame\Tests\Modules;

use Flame\Tester\MockTestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Ondřej Záruba
 */
class LatteMacrosProviderTest extends MockTestCase
{
	private $controlMock;

	protected function setUp()
	{
		parent::setUp();
		$this->controlMock = $this->mockista->create('Nette\Application\UI\Control', array(
			'getPresenter' => function () {return false;}
		));
	}

	public function testLatteMacrosProvider()
	{
		/** @var \Nette\Bridges\ApplicationLatte\Template $template */
		$template= $this->getContext()->getByType('Nette\Application\UI\ITemplateFactory')->createTemplate($this->controlMock);
		$compiler = $template->getLatte()->getCompiler();
		$reflectionProperty = new \ReflectionProperty($compiler, 'macros');
		$reflectionProperty->setAccessible(true);
		$macros = $reflectionProperty->getValue($compiler);
		$reflectionProperty->setAccessible(false);
		Assert::true(array_key_exists('FlameTestMacro', $macros));
	}
}

\run(new LatteMacrosProviderTest(getContainer()));