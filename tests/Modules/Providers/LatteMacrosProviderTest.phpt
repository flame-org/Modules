<?php

namespace Flame\Tests\Modules\Providers;

use Flame\Tester\TestCase;
use Nette\DI\MissingServiceException;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Ondřej Záruba
 */
class LatteMacrosProviderTest extends TestCase
{
	public function testLatteMacrosProvider()
	{
		try {
			$service = $this->getContext()->getService('nette.latteFactory');
		} catch (MissingServiceException $e) {
			$service = $this->getContext()->getService('nette.latte');
		}
		/** @var \Latte\Engine $engine */
		$engine = $service->create();
		Assert::true(count($engine->onCompile) >= 1);
	}
}

\run(new LatteMacrosProviderTest(getContainer()));