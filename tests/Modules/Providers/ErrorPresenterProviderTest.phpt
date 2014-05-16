<?php

namespace Flame\Tests\Modules\Providers;

use Flame\Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Ondřej Záruba
 */
class ErrorPresenterProviderTest extends TestCase
{
	public function testErrorPresenter()
	{
		/** @var \Nette\Application\Application $application */
		$application = $this->getContext()->getService('application');
		Assert::same('Flame:Module:Error', $application->errorPresenter);
	}
}

\run(new ErrorPresenterProviderTest(getContainer()));