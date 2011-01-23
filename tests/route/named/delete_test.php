<?php

declare(encoding='UTF-8');
namespace JoltTest\Route\Named;

use \Jolt\Route\Named\Delete,
	\JoltTest\TestCase;

require_once 'jolt/route/named/delete.php';

class DeleteTest extends TestCase {

	public function testNewDeleteRoute_RequestMethodIsDelete() {
		$route = new Delete('/user', 'User', 'index');
		$this->assertEquals('DELETE', $route->getRequestMethod());
	}
}