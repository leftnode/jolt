<?php

declare(encoding='UTF-8');
namespace Jolt;

class TestCaseTest extends TestCase {
	
	public function testBuildingAbstractRouteReturnsJoltRouteObject() {
		$abstract_route = $this->buildMockAbstractRoute();
		$this->assertTrue($abstract_route instanceof \Jolt\Route);
	}
	
	public function testBuildingNamedRouteReturnsJoltRouteNamedObject() {
		$named_route = $this->buildMockNamedRoute('/user', 'User', 'addAction');
		$this->assertTrue($named_route instanceof \Jolt\Route_Named);
	}
	
	public function testBuildingRestfulRouteReturnsJoltRestfulNamedObject() {
		$restful_route = $this->buildMockRestfulRoute('/user', 'User');
		$this->assertTrue($restful_route instanceof \Jolt\Route_Restful);
	}
}