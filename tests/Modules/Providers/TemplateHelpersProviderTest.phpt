<?php

namespace Flame\Tests\Modules\Providers;

use Flame\Tester\TestCase;
use Nette\DI\MissingServiceException;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Ondřej Záruba
 */
class TemplateHelpersProviderTest extends TestCase
{
	public function testTemplateHelper()
	{
		/** @var \Latte\Engine $engine */
		try {
			$service = $this->getContext()->getService('nette.latteFactory');
		} catch (MissingServiceException $e) {
			$service = $this->getContext()->getService('nette.latte');
		}
		/** @var \Latte\Engine $engine */
		$engine = $service->create();
		$helpers = $engine->getFilters();
		Assert::true(array_key_exists('flamehelper', $helpers));
		Assert::type('TestHelper', $helpers['flamehelper'][0]);
		Assert::same('process', $helpers['flamehelper'][1]);
		Assert::true(array_key_exists('flamehelper2', $helpers));
		Assert::type('TestHelper', $helpers['flamehelper2'][0]);
		Assert::same('process', $helpers['flamehelper2'][1]);
		Assert::true(array_key_exists('flamehelpertype2', $helpers));
		Assert::type('TestHelper2', $helpers['flamehelpertype2'][0]);
		Assert::same('process', $helpers['flamehelpertype2'][1]);
	}
}

\run(new TemplateHelpersProviderTest(getContainer()));