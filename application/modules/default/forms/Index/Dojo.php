<?php
class Default_Form_Index_Dojo extends Zend_Dojo_Form {
	
	public function init() {
		$this->setMethod ( 'post' );
		$this->setName ( 'dojosample' );
		$this->setAttribs(array(
				'onsubmit' => 'javascript:return false;'
		));
		/*$this->log = $this->createElement ( 'SimpleTextarea', 'log', array (
				'label' => false,
				'readonly'=> 'readonly',
				'formatLength' => 'long' 
		) );
		*/
		
		$this->message = $this->createElement('ValidationTextBox', 'message',array(
				'label'	=> false,
				'required'	=> true,
				'trim'	=> true,
				'invalidMessage' => 'Invalid Amount',
				'focusOnLoad'        => true,
				'placeholder'	=> 'Enter Chat Message'
		));
		$this->addElements(array(
				//$this->log,
				$this->message
		));
	}

}