<?php

class Default_IndexController extends Zend_Controller_Action {
	
	public function init() {
		$this->view->dojo()->enable();
		/* Initialize action controller here */
	}
}