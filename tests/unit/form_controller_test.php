<?php

declare(encoding='UTF-8');
namespace jolt_test;

use \jolt\form_controller,
	\jolt_test\testcase;

require_once('jolt/form_controller.php');

class form_controller_test extends testcase {

	private $formController = NULL;

	public function setUp() {
		$this->formController = $this->getMockForAbstractClass('\Jolt\FormController');
	}

	public function tearDown() {
		$this->formController = NULL;
	}

	public function testAddError_OverwritesExistingMessages() {
		$field = 'username';
		$msg1 = 'Error with the username';
		$msg2 = 'Make the username nonempty';

		$this->formController->addError($field, $msg1);
		$this->assertEquals($msg1, $this->formController->error($field));

		$this->formController->addError($field, $msg2);
		$this->assertEquals($msg2, $this->formController->error($field));
	}

	public function testError_ReturnsNullForMissingField() {
		$this->assertNull($this->formController->error('username'));
	}

	public function testSetId_IsTrimmed() {
		$id = '   id   ';
		$idTrimmed = 'id';

		$this->formController->setId($id);
		$this->assertEquals($idTrimmed, $this->formController->getId());
	}

	public function testSetName_IsTrimmed() {
		$name = '   name   ';
		$nameTrimmed = 'name';

		$this->formController->setName($name);
		$this->assertEquals($nameTrimmed, $this->formController->getName());
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetData_MustBeArray() {
		$this->formController->setData('not an array');
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetErrors_MustBeArray() {
		$this->formController->setErrors('not an array');
	}

	public function testGetDataCount_ReturnsNumberOfElementsInData() {
		$data = array('a' => 'first', 'b' => 'second', 'c' => 'third');
		$count = 3;

		$this->formController->setData($data);
		$this->assertEquals($count, $this->formController->getDataCount());
	}

}