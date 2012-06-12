<?php

class Default_IndexController extends Zend_Controller_Action {
	
	public function init() {
		/* Initialize action controller here */
	}
	
	public function indexAction() {
	}
	public function testingAction(){
		echo "this is what is written in the controller";
		echo "HAHAHA!";
		return;
	}
}

