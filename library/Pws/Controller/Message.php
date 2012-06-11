<?php
class Pws_Controller_Message extends Pws_Controller_Abstracts_MessageAbstract {
	protected $_response;
	public function beforeMessage() {
		/**
		 * Dummy message
		 *
		 * @var $message array
		 */
		$message = array (
				'module' => 'default',
				'controller' => 'index',
				'action' => 'testing' 
		);
		$this->_message = Zend_Json::encode ( $message );
		
		$message = Zend_Json::decode ( $this->_message );

		$request = new Zend_Controller_Request_Http();
		$request->setModuleName($message['module'])->setControllerName($message['controller'])->setActionName($message['action']);
		//$message['action'],$message['controller'],$message['module']
		
		$response = new Zend_Controller_Response_Http();
		
		// Get the front controller instance
		$frontController = Zend_Controller_Front::getInstance ()->returnResponse(true);

		$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini');
		$config = $config->toArray();
		$prefixDefaultModule = isset($config[APPLICATION_ENV]['resources']['frontController']['prefixDefaultModule'])?
								$config[APPLICATION_ENV]['resources']['frontController']['prefixDefaultModule']==1:false;
		$dispatcher = $frontController->getDispatcher()->setParam('prefixDefaultModule', $prefixDefaultModule);
		
		$dispatcher->dispatch($request, $response);
		
		$this->_response = $response->getBody ( false );
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
				$this->Server->wsSend ( $id, "Visitor {$this->_client_id} ($ip) said \"$this->_response\"" );
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
}