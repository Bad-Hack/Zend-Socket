<?php
class Pws_Exception {
	protected $_zend_exception = null;
	protected $_exception = null;
	
	/**
	 * Accept Zend_Exception | Exception class objects
	 * 
	 * @param Zend_Exception|Exception $ze        	
	 */
	public function __construct($ze = null) {
		if ($ze instanceof Zend_Exception) {
			$this->_zend_exception = $ze;
		} else if ($ze instanceof Exception) {
			$this->_exception = $ze;
		} else if (is_string ( $ze )) {
			$this->_exception = new Exception ( $ze );
		} else {
			$this->_exception = new Exception ( "Error" );
		}
	}
	public function getMessage() {
		if (PWS_ENV === "production") {
			return "Error";
		}
		if (null != $this->_zend_exception) {
			return $this->_zend_exception->getMessage ();
		} else if (null != $this->_exception) {
			return $this->_exception->getMessage ();
		}
		return "Error";
	
	}
	public function getException() {
		return $this->_zend_exception == null ? $this->_exception : $this->_zend_exception;
	}
}