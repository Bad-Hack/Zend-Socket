<?php

class Default_IndexController extends Zend_Controller_Action {
	
	public function init() {
		$this->view->dojo()->enable();
		/* Initialize action controller here */
	
	}
	
	public function indexAction() {
		$this->view->form = new Default_Form_Index_Dojo();
	}
	public function testingAction() {
	}
}