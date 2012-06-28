<?php
class Pws_Controller_Message extends Pws_Controller_Abstracts_MessageAbstract {
	
	protected $_response;
	protected $_defaultDataType = "json";
	protected $_responseClients;
	
	/**
	 * hook before the message is processed
	 *
	 * @see Pws_Controller_Abstracts_MessageAbstract::beforeMessage();
	 */
	public function beforeMessage() {
		$this->_decodeMessage ();
	}
	
	/**
	 * Execute the function when messages is processed
	 *
	 * @see Pws_Controller_Abstracts_MessageAbstract::onMessage()
	 */
	public function onMessage() {
		// Close the client if message length was 0
		if ($this->_message_length == 0) {
			$this->Server->wsClose ( $this->_client_id );
			return;
		}
		// Else send the message according
		$this->_response = htmlentities ( $this->_response );
		
		$this->sendMessage ( $this->_response, $this->_responseClients );
	}
	
	/**
	 * Send message to the client
	 *
	 * @param mixed $message        	
	 * @param array $clients        	
	 */
	public function sendMessage($message, array $clients = array(), array $error = array()) {
		
		$message = $this->_generateValidMessage ( $message, $error );
		if (empty ( $clients )) {
			if (empty ( $this->_responseClients )) {
				array_push ( $clients, $this->_client_id );
			} else {
				$clients = $this->_responseClients;
			}
		}
		foreach ( $clients as $clientID ) {
			if ($this->Server->hasClient ( $clientID )) {
				$this->Server->wsSend ( $clientID, $message );
			}
		}
	}
	
	/**
	 * Decode the incomming message and save the clients and response
	 *
	 * @param mixed $message        	
	 */
	private function _decodeMessage($message = null) {
		if (null == $message) {
			$message = $this->_message;
		}
		$message = Zend_Json::decode ( $message );
		
		// Set Action,Controller & Module for Simple Request
		$action = isset ( $message ['action'] ) && $message ['action'] !== false ? $message ['action'] : 'index';
		$controller = isset ( $message ['controller'] ) && $message ['controller'] !== false ? $message ['controller'] : 'index';
		$module = isset ( $message ['module'] ) && $message ['module'] !== false ? $message ['module'] : 'default';
		
		if (isset ( $message ['clients'] ) && is_array ( $message ['clients'] )) {
			$clients = $message ['clients'];
		}
		// Set the params for the request
		$params = array ();
		if (isset ( $message ['params'] ) && ! is_array ( $message ['params'] ) && is_string ( $message ['params'] )) {
			$params = array (
					$message ['params'] 
			);
		} elseif (isset ( $message ['params'] ) && is_array ( $message ['params'] )) {
			$params = $message ['params'];
		}
		
		$config = new Zend_Config_Ini ( APPLICATION_PATH . '/configs/application.ini' );
		$config = $config->toArray ();
		
		$request = new Zend_Controller_Request_Http ();
		$request->setModuleName ( $module )->setControllerName ( $controller )->setActionName ( $action );
		
		$baseUrl = "";
		if (isset ( $config ['production'] ['resources'] ['frontController'] ['baseUrl'] )) {
			$baseUrl = $config ['production'] ['resources'] ['frontController'] ['baseUrl'];
		} else if (isset ( $config ['development'] ['resources'] ['frontController'] ['baseUrl'] )) {
			$baseUrl = $config ['development'] ['resources'] ['frontController'] ['baseUrl'];
		}
		$frontController = Zend_Controller_Front::getInstance ()->returnResponse ( true );
		
		// Check if default module prefix is enabled or not
		$prefixDefaultModule = isset ( $config [APPLICATION_ENV] ['resources'] ['frontController'] ['prefixDefaultModule'] ) ? $config [APPLICATION_ENV] ['resources'] ['frontController'] ['prefixDefaultModule'] == 1 : false;
		
		$response = new Zend_Controller_Response_Http ();
		
		$frontController->getDispatcher ()->setParam ( 'prefixDefaultModule', $prefixDefaultModule )->dispatch ( $request, $response );
		
		/**
		 * This code of block was set to patch the render view and thus the
		 * PHTML file
		 * Please maintain this code if future bugs are found.
		 * @solution : This code is needed if the views are not initialized in
		 * application.ini
		 */
		/*
		 * $dispatcher = $frontController->getDispatcher (); if (!
		 * $dispatcher->isDispatchable ( $request )) { $controller =
		 * $request->getControllerName (); if (! $dispatcher->getParam (
		 * 'useDefaultControllerAlways' ) && ! empty ( $controller )) {
		 * require_once 'Zend/Controller/Dispatcher/Exception.php'; throw new
		 * Zend_Controller_Dispatcher_Exception ( 'Invalid controller specified
		 * (' . $request->getControllerName () . ')' ); } $className =
		 * $dispatcher->getDefaultControllerClass ( $request ); } else {
		 * $className = $dispatcher->getControllerClass ( $request ); if (!
		 * $className) { $className = $dispatcher->getDefaultControllerClass (
		 * $request ); } } $className = $dispatcher->formatClassName ( $module,
		 * $className ); /** Instantiate controller with request, response, and
		 * invocation arguments; throw exception if it's not an action
		 * controller
		 */
		// $controller = new $className ( $request, $dispatcher->getResponse (),
		// $dispatcher->getParams () );
		// $controller->render ();
		
		$this->_response = $response->getBody ();
		$this->_responseClients = array ();
	}
	
	/**
	 * Generate Valid Message with given parameters
	 *
	 * @param mixed $message        	
	 */
	private function _generateValidMessage($message = null, array $error = array()) {
		// Convert null message to string
		$message = null == $message ? "" : $message;
		
		$messageArray = array ();
		if (is_string ( $message )) {
			$messageArray ['message'] = $message;
			$messageArray ['dataType'] = $this->getDefaultDataType ();
		} else if (is_array ( $message )) {
			$messageArray ['dataType'] = isset ( $message ['dataType'] ) && $message ['dataType'] != null ? $message ['dataType'] : $this->getDefaultDataType ();
			$messageArray ['response'] = isset ( $message ['response'] ) && $message ['response'] != null ? $message ['response'] : $this->_response;
		}
		if (! empty ( $error )) {
			$messageArray ['status'] = $error;
		}
		
		if (strtolower ( $this->getDefaultDataType () ) == "json") {
			$responseMessage = Zend_Json::encode ( $messageArray );
		}
		return $responseMessage;
	}
	
	/**
	 * Get the default datatype
	 * this value is required for sending the response in selected format
	 *
	 * @return string
	 */
	public function getDefaultDataType() {
		return $this->_defaultDataType;
	}
	
	/**
	 * Set the default dataType
	 *
	 * @param string $dataType        	
	 * @return Pws_Controller_Message | boolean
	 */
	public function setDefaultDataType($dataType) {
		if (is_string ( $dataType )) {
			$this->_defaultDataType = $dataType;
			return $this;
		}
		return false;
	}
}