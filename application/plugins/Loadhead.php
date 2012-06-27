<?php
class Plugin_Loadhead extends Zend_Controller_Plugin_Abstract {
	/**
	 * Store the loaded scripts.
	 * Just to keep record
	 * 
	 * @var array $_loaded
	 */
	protected $_loaded = array ();
	
	/**
	 * Store the request variable
	 * 
	 * @var Zend_Controller_Request_Abstract $_request
	 */
	protected $_request;
	
	protected $view;
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		
		// Store the request varible for future use
		$this->_request = $request;
		
		// Set the absolute path for the public folder
		Zend_Registry::isRegistered('absolutePublicPath')?:Zend_Registry::set('absolutePublicPath', $_SERVER['DOCUMENT_ROOT'].$this->_request->getBasePath());
		
		// Store the ViewRenderer
		$this->view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
		
		// Load head scripts by default
		$this->_loadHead();
		
		/*echo "<pre>";
		print_r(APPLICATION_PATH);
		echo "<br />PathInfo: <br />";
		print_r($request->getPathInfo());
		echo "<br />Server Variable: <br />";
		print_r($_SERVER);
		echo "<br />Class Methods: <br />";
		print_r(get_class_methods(get_class($request)));
		echo "<br />Class Variables: <br />";
		print_r(get_class_vars((get_class($request))));
		echo "<br />Request Data: <br />";
		print_r($request);
		echo "<br />Get Base Path: <br />";
		print_r($request->getBasePath());
		die;
		*/
	}
	
	// Loading the JS Dynamically as per the configurations
	private function _loadJs() {
		if (isset ( $this->_loaded ['js'] ) && $this->_loaded ['js'] == true) {
			return true;
		}
		try {
			$js_path = Zend_Registry::get('absolutePublicPath').DS."js".DS."js.xml";
			$js_data = new Zend_Config_Xml ( $js_path );
		} catch ( Zend_Exception $ex ) {
			return false;
		}
		
		$module_name = $this->_request->module;
		
		// Collect all mandatory and module specific javascripts
		$javascripts = array ();
		$scripts = $js_data->toArray ();
		
		if (isset ( $scripts ['mandatory'] ) && isset ( $scripts ['mandatory'] [APPLICATION_ENV] ) && isset ( $scripts ['mandatory'] [APPLICATION_ENV] ['javascript'] )) {
			$mandatory_scripts = $scripts ['mandatory'] [APPLICATION_ENV] ['javascript'];
			if (! is_array ( $mandatory_scripts )) {
				$mandatory_scripts = array (
						$mandatory_scripts 
				);
			}
			$mandatory_scripts = array_unique ( $mandatory_scripts );
			foreach ( $mandatory_scripts as $mandatory_script ) {
				$this->view->headScript ()->appendFile ( './js/mandatory/' . $mandatory_script, 'text/javascript' );
			}
		}
		
		// Module Wise Javascripts
		if (isset ( $scripts ['modules'] [$module_name] ) && isset ( $scripts ['modules'] [$module_name] [APPLICATION_ENV] ) && isset ( $scripts ['modules'] [$module_name] [APPLICATION_ENV] ['javascript'] )) {
			
			$module_scripts = $scripts ['modules'] [$module_name] [APPLICATION_ENV] ['javascript'];
			if (! is_array ( $module_scripts )) {
				$module_scripts = array (
						$module_scripts 
				);
			}
			foreach ( $module_scripts as $module_script ) {
				$this->view->headScript ()->appendFile ( './js/modules/' . $module_name . '/' . $mandatory_script, 'text/javascript' );
			}
		}
	}
	private function _loadCss() {
		if (isset ( $this->_loaded ['css'] ) && $this->_loaded ['css'] == true) {
			return true;
		}
		try {
			$css_data = new Zend_Config_Xml ( './css/css.xml' );
		} catch ( Zend_Exception $ex ) {
			return false;
		}
		
		$module_name = $this->_request->module;
		
		// Collect all mandatory and module specific css
		$stylesheets = array ();
		$sheets = $css_data->toArray ();
		
		if (isset ( $sheets ['mandatory'] ) && isset ( $sheets ['mandatory'] [APPLICATION_ENV] ) && isset ( $sheets ['mandatory'] [APPLICATION_ENV] ['stylesheet'] )) {
			$mandatory_sheets = $sheets ['mandatory'] [APPLICATION_ENV] ['stylesheet'];
			if (! is_array ( $mandatory_sheets )) {
				$mandatory_sheets = array (
						$mandatory_sheets 
				);
			}
			$mandatory_sheets = array_unique ( $mandatory_sheets );
			foreach ( $mandatory_sheets as $mandatory_sheet ) {
				$this->view->headLink ()->appendStylesheet ( './css/mandatory/' . $mandatory_sheet );
			}
		}
		
		// Module Wise Stylesheet
		if (isset ( $sheets ['modules'] [$module_name] ) && isset ( $sheets ['modules'] [$module_name] [APPLICATION_ENV] ) && isset ( $sheets ['modules'] [$module_name] [APPLICATION_ENV] ['stylesheet'] )) {
			
			$module_sheets = $sheets ['modules'] [$module_name] [APPLICATION_ENV] ['stylesheet'];
			if (! is_array ( $module_sheets )) {
				$module_sheets = array (
						$module_sheets 
				);
			}
			foreach ( $module_sheets as $module_sheet ) {
				$this->view->headScript ()->appendStylesheet ( './css/modules/' . $module_name . '/' . $module_sheet );
			}
		}
	}
	
	private function _loadHead() {
		$this->_loadCss ();
		$this->_loaded ['css'] = true;
		
		$this->_loadJs ();
		$this->_loaded ['js'] = true;
	}
}