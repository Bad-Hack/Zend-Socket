<?php

class Default_IndexController extends Zend_Controller_Action {
	
	public function init() {
		/* Initialize action controller here */
	}
	
	public function indexAction() {
	}
	public function testingAction(){
		print_r("This is testing");
		die;
	}
}

