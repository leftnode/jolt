<?php

declare(encoding='UTF-8');
namespace JoltApp;

use \Jolt\Controller;

require_once 'Jolt/Controller.php';

class Index extends Controller {
	
	public function indexAction() {
		echo 'Hi, from Jolt!', PHP_EOL;
	}
	
}