<?php
abstract class Pws_Controller_Abstracts_OpenAbstract extends Pws_Controller_Abstracts_Abstract {
	
	public function setOptions(array $options) {
		$this->_client_id = $options ['client_id'];
	}
	public function beforeOpen() {
	}
	public function onOpen() {
	}
	public function afterOpen() {
	}
}