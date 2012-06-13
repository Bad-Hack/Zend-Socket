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
		$this->sendMessage ( $this->_response, $this->_responseClients );
	}
	
	/**
	 * Send message to the client
	 *
	 * @param mixed $message        	
	 * @param array $clients        	
	 */
	public function sendMessage($message, array $clients = array()) {
		$message = $this->_generateValidMessage ( $message );
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
		
		$request = new Zend_Controller_Request_Simple($action,$controller,$module);
		$request = new Zend_Controller_Request_Http();
		$request->setModuleName($module)->setControllerName($controller)->setActionKey($action);
		
		$baseUrl = "";
		if(isset($config['production'] ['resources'] ['frontController'] ['baseUrl'])){
			$baseUrl = $config['production'] ['resources'] ['frontController'] ['baseUrl'];
		}else if(isset($config['development'] ['resources'] ['frontController'] ['baseUrl'])){
			$baseUrl = $config['development'] ['resources'] ['frontController'] ['baseUrl'];
		}
		$requestUri = $baseUrl."/".$module."/".$controller."/".$action;
		echo $requestUri;
		//$requestUri = $module."/".$controller."/".$action;
		$request->setRequestUri($requestUri);
		//$_SERVER["REQUEST_URI"] = $requestUri;  
		//$router = new Zend_Controller_Router_Rewrite();
		//$router->route($request);
		
		$frontController = Zend_Controller_Front::getInstance()->returnResponse(true)->setParam('noViewRenderer', false)->setParam('noErrorHandler', false);
		//$frontController->setRouter($router);
		
		// Check if default module prefix is enabled or not
		$prefixDefaultModule = isset ( $config [APPLICATION_ENV] ['resources'] ['frontController'] ['prefixDefaultModule'] ) ? $config [APPLICATION_ENV] ['resources'] ['frontController'] ['prefixDefaultModule'] == 1 : false;
		$dispatcher = $frontController->getDispatcher();
		$dispatcher->setParam('prefixDefaultModule', $prefixDefaultModule);
		
		//$frontController->setDispatcher($dispatcher);
		
		$response = new Zend_Controller_Response_Http();
		
		$frontController->setParam('prefixDefaultModule', $prefixDefaultModule);
		$frontController->dispatch($request, $response);
		//$frontController->setDefaultModule($module);
		//$frontController->setDefaultControllerName($controller);
		//$frontController->setDefaultAction($action);
		//$response = $dispatcher->getResponse();
		
		$this->_response = $response->getBody();
		$this->_responseClients = array();
		
	}
	
	/**
	 * Generate Valid Message with given parameters
	 *
	 * @param mixed $message        	
	 */
	private function _generateValidMessage($message = null) {
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
	
	public function temp(){
		// $action, $controller, $module, $params
		$request = new Zend_Controller_Request_Http ();
		
		$request->setModuleName ( $module );
		$request->setControllerName ( $controller );
		$request->setActionName ( $action );
		$_SERVER['REQUEST_URI'] = $config ["development"] ['resources'] ['frontController'] ['baseUrl']."/".$module."/".$controller."/".$action;
		//echo $_SERVER['REQUEST_URI'];
		$response = new Zend_Controller_Response_Http ();
		
		// Get the front controller instance
		$frontController = Zend_Controller_Front::getInstance ()->returnResponse ( true );
		
		// Check if default module prefix is enabled or not
		$prefixDefaultModule = isset ( $config [APPLICATION_ENV] ['resources'] ['frontController'] ['prefixDefaultModule'] ) ? $config [APPLICATION_ENV] ['resources'] ['frontController'] ['prefixDefaultModule'] == 1 : false;
		
		$dispatcher = $frontController->getDispatcher ()->setParam ( 'prefixDefaultModule', $prefixDefaultModule );
		
		//$frontController->setDispatcher ( $dispatcher )->dispatch ( $request, $response );
		$dispatcher->dispatch($request, $response);
		
		$this->_response = $response->getBody();
		
		$this->_responseClients = empty ( $clients ) ? array () : $clients;
	}
}