<?php
class Default_Form_Index_Dojo extends Zend_Dojo_Form {
	public function init() {
		
		$this->setMethod ( 'post' );
		$this->setAttribs ( array (
				'onsubmit' => 'javascript: return false;' 
		) );
		$this->setName ( 'dojosample' );
		$this->logElement = $this->createElement ( 'SimpleTextarea', 'log', array (
				'label' => false,
				'required' => false 
		) );
		$this->logElement->setAttribs ( array (
				'readonly' => 'readonly' ,
				'style'	=> 'resize:none;overflow:hidden'
		) );
		$this->addElement ( 'text', 'message', array (
				'label' => false,
				'required' => true,
				'invalidateMessage' => 'Please enter some text' 
		) );
	}
}