<?php
class Pws_Exception extends Exception {
	protected $_exception = null;
	
	/**
	 * Accept Zend_Exception | Exception class objects
	 * 
	 * @param Exception $ze        	
	 */
	public function __construct(Exception $ze = null) {
		if ($ze instanceof Exception) {
			$this->_exception = $ze;
		} else if (is_string ( $ze )) {
			$this->_exception = new Exception ( $ze );
		} else {
			$this->_exception = new Exception ( "Error" );
		}
	}
	public function getPwsMessage() {
		if (PWS_ENV === "production") {
			return "Error";
		}
		if (null != $this->_exception) {
			return $this->_exception->getMessage ();
		}
		return "Error";
	
	}
	public function getException() {
		return $this->_exception;
	}
}