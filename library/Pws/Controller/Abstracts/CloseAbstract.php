<?php
abstract class Pws_Controller_Abstracts_CloseAbstract extends Pws_Controller_Abstracts_Abstract {
	protected $_status;
	
	public function setOptions(array $options) {
		$this->_status = $options ['status'];
	}
	public function beforeClose() {
	}
	public function onClose() {
	}
	public function afterClose() {
	}
}