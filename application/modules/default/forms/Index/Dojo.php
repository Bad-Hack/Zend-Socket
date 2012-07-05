<?php
class Default_Form_Index_Dojo extends Zend_Dojo_Form {
	public function init() {
		
		$this->setMethod ( 'post' );
		$this->setName ( 'dojosample' );
		$this->addElement ( 'DateTextBox', 'datetext', array (
				'label' => 'Date:',
				'required' => true,
				'invalidMessage' => 'Invalid date specified.',
				'formatLength' => 'long' 
		) );
	}
}