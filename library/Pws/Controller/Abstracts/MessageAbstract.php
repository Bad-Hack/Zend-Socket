<?php
abstract class Pws_Controller_Abstracts_MessageAbstract extends Pws_Controller_Abstracts_Abstract {
	protected $_message;
	protected $_message_length;
	protected $_binary;
	public function setOptions(array $options) {
		$this->_message = $options ['message'];
		$this->_client_id = $options ['client_id'];
		$this->_message_length = $options ['message_length'];
		$this->_binary = $options ['binary'];
	}
	public function beforeMessage() {
	}
	public function onMessage() {
	}
	public function afterMessage() {
	}
}