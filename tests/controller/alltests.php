<?php

declare(encoding='UTF-8');
namespace JoltTest\Controller;

require_once 'controller/locator_test.php';

class AllTests {
	
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Controller Locator Tests');

		$suite->addTestSuite('\JoltTest\Controller\LocatorTest');

		return $suite;
	}
	
}
