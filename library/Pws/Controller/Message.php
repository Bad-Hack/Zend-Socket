<?php
class Pws_Controller_Message extends Pws_Controller_Abstracts_MessageAbstract {
	
	protected $_response;
	protected $_defaultDataType;
	
	
	public function beforeMessage() {
		$this->_decodeMessage ();
	
	}
	public function onMessage() {
		
		$ip = $this->_client_ip;
		
		// check if message length is 0
		if ($this->_message_length == 0) {
			$this->Server->wsClose ( $this->_client_id );
			return;
		}
		
		foreach ( $this->Server->wsClients as $id => $client ) {
			if ($id != $this->_client_id) {
				$this->Server->wsSend ( $id, "Visitor {$this->_client_id} ($ip) said \"{$this->_response}\"" );
			}
		}
		// The speaker is the only person in the room. Don't let them feel
		// lonely.
		/*
		 * if (sizeof ( $this->Server->wsClients ) == 1) { $this->Server->wsSend
		 * ( $this->_client_id, "There isn't anyone else in the room, but I'll
		 * still listen to you. --Your Trusty Server" ); } else { // Send the
		 * message to everyone but the person who said it foreach (
		 * $this->Server->wsClients as $id => $client ) { if ($id !=
		 * $this->_client_id) { $this->Server->wsSend ( $id, "Visitor
		 * {$this->_client_id} ($ip) said \"$this->_message\"" ); } } }
		 */
	}
	
	private function _decodeMessage() {
		$message = $this->_message;
		try {
			$message = Zend_Json::decode ( $message );
		} catch ( Zend_Exception $e ) {
			throw new Pws_Exception ( $e );
			return;
		}
		$this->_message = Zend_Json::encode ( $message );
		
		$message = Zend_Json::decode ( $this->_message );
		
		$request = new Zend_Controller_Request_Simple ( $message ['action'], $message ['controller'], $message ['module'] );
		
		// $message['action'],$message['controller'],$message['module']
		
		$response = new Zend_Controller_Response_Http ();
		
		// Get the front controller instance
		$frontController = Zend_Controller_Front::getInstance ()->returnResponse ( true );
		
		$config = new Zend_Config_Ini ( APPLICATION_PATH . '/configs/application.ini' );
		$config = $config->toArray ();
		$prefixDefaultModule = isset ( $config [APPLICATION_ENV] ['resources'] ['frontController'] ['prefixDefaultModule'] ) ? $config [APPLICATION_ENV] ['resources'] ['frontController'] ['prefixDefaultModule'] == 1 : false;
		$dispatcher = $frontController->getDispatcher ()->setParam ( 'prefixDefaultModule', $prefixDefaultModule );
		
		$dispatcher->dispatch ( $request, $response );
		
		$this->_response = $response->getBody ( false );
	}
	
	public function sendMessage($message, array $clients = array()) {
		$message = $this->_generateValidMessage($message);
		foreach($clients as $clientID){
			if($this->Server->hasClient($clientID)){
				$this->Server->wsSend($clientID, $message);
			}
		}
	}
	private function _generateValidMessage($message=null){
		// Convert null message to string
		$message = null==$message?"":$message;
		
		$messageArray = array();
		if(is_string($message)){
				$messageArray['message'] = $message;
		}else if(is_array($message)){
			$messageArray['dataType'] = isset($message['dataType']) && $message['dataType']!=null?$message['dataType']:$this->_getDefaultDataType();
		}
	}
	public function _getDefaultDataType(){
		return $this->_defaultDataType;
	}
	
}