<?php
abstract class Pws_Controller_Abstracts_Abstract {
	public $Server;
	protected $_client_id;
	protected $_client_ip;
	
	public function setOptions(array $options) {
	}
	
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