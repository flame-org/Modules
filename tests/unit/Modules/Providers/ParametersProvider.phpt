<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */

namespace Flame\Tests\Modules\Providers;

use Flame\Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


class ParametersProviderTest extends TestCase
{

	public function testSetParameters()
	{
		$parameter = $this->getContextParameter('test');
		Assert::same('param value', $parameter);
	}

}

\run(new ParametersProviderTest(getContainer()));