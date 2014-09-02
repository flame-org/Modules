<?php

namespace Flame\Tests\Modules\Providers;

use Flame\Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Ondřej Záruba
 */
class PresenterMappingProviderTest extends TestCase
{
	public function testMappingProvider()
	{
		/** @var \Nette\Application\PresenterFactory $application */
		$presenterFactory = $this->getContext()->getService('nette.presenterFactory');
		$reflectionProperty = new \ReflectionProperty($presenterFactory, 'mapping');
		$reflectionProperty->setAccessible(true);
		$mappings = $reflectionProperty->getValue($presenterFactory);
		$reflectionProperty->setAccessible(false);
		Assert::true(array_key_exists('FlameModule', $mappings));
		$expected = array(
			'Flame\Test\\',
			'*Module\\',
			'Presenters\*Presenter'
		);
		Assert::equal($expected, $mappings['FlameModule']);
	}
}

\run(new PresenterMappingProviderTest(getContainer()));
