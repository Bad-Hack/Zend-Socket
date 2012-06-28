<?php

class Default_IndexController extends Zend_Controller_Action {
	
	public function init() {
		/* Initialize action controller here */
		
	}
	
	public function indexAction() {
		$this->view->assign('tirth',"Tirth Is Typing");
	}
	public function testingAction() {
		$this->view->tirth = "Hello Tirth";
	}
}

