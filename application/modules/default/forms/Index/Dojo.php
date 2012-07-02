<?php
class Default_Form_Index_Dojo extends Zend_Dojo_Form {
	public function init() {
		
		$this->setMethod ( 'post' );
		$this->setAttrib('onsubmit','javascript: return false;');
		$this->setName ( 'dojosample' );
		$this->addElement ( 'textarea', 'log', array (
				'label' => false,
				'required' => false,
				'readonly' => 'readonly',
				'max-height' => '100px' 
		) );
		$this->addElement('text','message',array(
				'label'	=> false,
				'required' => true,
				'invalidateMessage' => 'Please enter some text',
		));
	}
}