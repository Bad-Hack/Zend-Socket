<?php
abstract class Pws_Controller_Abstracts_CloseAbstract extends Pws_Controller_Abstracts_Abstract {
	protected $_client_id;
	
	public function setOptions(array $options) {
		$this->_client_id = $options ['status'];
		$this->_client_id = $options ['client_id'];
	}
	public function beforeClose() {
	}
	public function onClose() {
	}
	public function afterClose() {
	}
}