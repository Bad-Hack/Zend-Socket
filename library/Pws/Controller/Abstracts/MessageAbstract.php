<?php
abstract class Pws_Controller_Abstracts_MessageAbstract extends Pws_Controller_Abstracts_Abstract {
	
	/**
	 * When a message is recived from a socket commenction its is stored in raw
	 * from in this variable
	 *
	 * @access protected
	 * @var string $_message
	 */
	protected $_message;
	
	/**
	 * To store the length of message received
	 *
	 * @access protected
	 * @var int $_message_length
	 */
	protected $_message_length;
	
	/**
	 * Storing the binary form of the received message
	 * 
	 * @access protected
	 * @var string $_binary
	 */
	protected $_binary;
	
	/**
	 * Setting the options as variables passed and available
	 * (non-PHPdoc)
	 * @see Pws_Controller_Abstracts_Abstract::setOptions()
	 * @access public
	 */
	public function setOptions(array $options) {
		$this->_message = $options ['message'];
		$this->_message_length = $options ['message_length'];
		$this->_binary = $options ['binary'];
	}
	
	/**
	 * 
	 */
	public function beforeMessage() {
	}
	public function onMessage() {
	}
	public function afterMessage() {
	}
}