<?php

namespace JoltTest;
use \Jolt\Registry;

require_once 'Jolt/Registry.php';

class JoltCore_Registry_RegistryTest extends TestCase {
	public function setUp() {
		Registry::reset();
	}
	
	public function testScalarElements() {
		Registry::reset();
		
		Registry::push('string', 'value');
		$this->assertEquals('value', Registry::pop('string'));
		
		Registry::push('integer', 10);
		$this->assertEquals(10, Registry::pop('integer'));
		
		Registry::push('float', 10.45);
		$this->assertEquals(10.45, Registry::pop('float'));
	}
	
	public function testComplexElements() {
		Registry::reset();
		
		$std_class = new \stdClass();
		$std_class->name = 'Vic Cherubini';
		Registry::push('std_class', $std_class);
		$this->assertEquals($std_class, Registry::pop('std_class'));
		
		$array = array('name' => 'Vic Cherubini', 'language_list' => array('php', 'perl', 'javascript', 'c++', 'c', 'sql'));
		Registry::push('array', $array);
		$this->assertEquals($array, Registry::pop('array'));
	}
	
	public function testDeletingElements() {
		Registry::reset();
		
		Registry::push('delete_me', 'delete me please');
		$this->assertEquals('delete me please', Registry::pop('delete_me', true));
		$this->assertEquals(NULL, Registry::pop('delete_me'));
	}
	
	public function testOverwritingElements() {
		Registry::reset();
		
		/* Disallowing overwrites. */
		Registry::push('do_not_overwrite', 'dno', false);
		$this->assertEquals('dno', Registry::pop('do_not_overwrite'));
		
		Registry::push('do_not_overwrite', 'dno2');
		$this->assertEquals('dno', Registry::pop('do_not_overwrite'));
		
		/* Allowing overwrites. */
		Registry::push('allow_overwrite', 'ao');
		$this->assertEquals('ao', Registry::pop('allow_overwrite'));
		
		Registry::push('allow_overwrite', 'ao2');
		$this->assertEquals('ao2', Registry::pop('allow_overwrite'));
		
		/* Disallowing overwrites, but deleting the element so it can be overwritten. */
		Registry::push('do_not_overwrite', 'dno', false);
		Registry::push('do_not_overwrite', 'dno2');
		$this->assertEquals('dno', Registry::pop('do_not_overwrite', true));
		
		Registry::push('do_not_overwrite', 'dno3', false);
		$this->assertEquals('dno3', Registry::pop('do_not_overwrite'));
	}
}