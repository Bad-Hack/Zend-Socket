<?php
abstract class Pws_Controller_Abstracts_Abstract {
	
	public $Server;
	protected $_client_id;
	protected $_client_ip;
	
	/**
	 * Set the value of variable
	 *
	 * @param string $name        	
	 * @param string $value        	
	 */
	final public function __set($name, $value) {
		$method = 'set' . $name;
		$this->$method ( $value );
	}
	
	/**
	 * Get the value of variable
	 *
	 * @param string $name        	
	 */
	final public function __get($name) {
		$method = 'get' . $name;
		return $this->$method ();
	}
	
	/**
	 * Magic call function for calling a method
	 *
	 * @param string $method        	
	 * @param array $arguments        	
	 */
	final public function __call($method, array $arguments) {
		
		// Automatic Set and Get Methods
		$type = substr ( $method, 0, 3 );
		
		if (strtolower ( $type ) == "set" || strtolower ( $type ) == "get") {
			$this->_callSettersGetters ( $method, $arguments );
		}
	}
	
	/**
	 * Call the magic setter and getter methods
	 *
	 * @param string $method        	
	 * @param array $arguments        	
	 * @throws Pws_Exception
	 * @return Pws_Controller_Abstracts_Abstract
	 */
	private function _callSettersGetters($method, array $arguments) {
		
		$type = substr ( $method, 0, 3 );
		
		$classMethod = substr ( $method, 3 );
		
		$variableName = $this->_createVariable ( $classMethod );
		
		$classVariables = array_keys ( get_class_vars ( get_class ( $this ) ) );
		
		if (in_array ( $variableName, $classVariables )) {
			if ($type == "get") {
				return $this->{$variableName};
			} elseif ($type == "set") {
				if (isset ( $arguments [0] )) {
					$this->{$variableName} = $arguments [0];
					return $this;
				} else {
					$this->{$variableName} = "";
					return $this;
				}
			}
		} else {
			throw new Pws_Exception ( "Invalid method call found: " . $method . "()" );
		}
	}
	
	/**
	 * Create varible name according to convention
	 * mainly the convention is as follows:
	 * Abc => _abc, AbcDEf => _abc_d_ef
	 * 
	 * @param string $method        	
	 */
	private function _createVariable($method) {
		$string = "";
		for($i = 0; $i < strlen ( $method ); $i ++) {
			if ($method [$i] == strtoupper ( $method [$i] )) {
				$string .= "_" . strtolower ( $method [$i] );
			} else {
				$string .= $method [$i];
			}
		}
		return $string;
	}
	
	/**
	 * Set varibles according to avaibility
	 * @param array $options
	 */
	final public function setOptions(array $options){
		foreach ($options as $key => $value) {
			$method = 'set' . str_replace(" ","",ucwords(str_replace("_"," ",$key)));
			$this->$method($value);
		}
		return $this;
	}
	
	/**
	 * final constructor for all the extending classes
	 * 
	 * @param Pws_Server $Server        	
	 * @param int $clientID        	
	 * @throws Exception
	 */
	final public function __construct(Pws_Server $Server, $clientID = null) {
		// Storing the Server Variable
		$this->Server = $Server;
		
		// Checking for client-IP
		if (null == $clientID) {
			throw new Exception ( "No Client ID specifed" );
		} else if (! isset ( $this->Server->wsClients [$clientID] )) {
			throw new Exception ( "Invalid Client ID" );
		}
		
		// Storing the Client-ID
		$this->_client_id = $clientID;
		// Calcuating the Client-IP
		$this->_client_ip = long2ip ( $this->Server->wsClients [$this->_client_id] [6] );
	}
}