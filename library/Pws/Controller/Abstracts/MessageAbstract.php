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
	 * Prototype for method before any message is processed
	 */
	public function beforeMessage() {
	}
	
	/**
	 * Prototype for method on processing message
	 */
	public function onMessage() {
	}
	
	/**
	 * Prototype for method after processing message
	 */
	public function afterMessage() {
	}
}