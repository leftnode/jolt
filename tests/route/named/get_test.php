<?php

declare(encoding='UTF-8');
namespace JoltTest\Route\Named;

use \Jolt\Route\Named\Get,
	\JoltTest\TestCase;

require_once 'jolt/route/named/get.php';

class GetTest extends TestCase {

	public function testNewGetRoute_RequestMethodIsGet() {
		$route = new Get('/user', 'User', 'index');
		$this->assertEquals('GET', $route->getRequestMethod());
	}
}