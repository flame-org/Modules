<?php
/**
 * Class ErrorPresenterProviderTest
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 25.07.13
 */

namespace modules\Tests;

use Flame\Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Ondřej Záruba
 */
class ErrorPresenterProviderTest extends TestCase
{
	public function testErrorPresenter()
	{
		/** @var \Nette\Application\Application $application */
		$application = $this->getContext()->getByType('Nette\Application\Application');
		Assert::same('Flame:Module:Error', $application->errorPresenter);
	}
}

\run(new ErrorPresenterProviderTest(getContainer()));