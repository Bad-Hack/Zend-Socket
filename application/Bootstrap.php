<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	
	public function _initModuleLoader() {
		$modelLoader = new Zend_Application_Module_Autoloader ( array (
				'namespace' => '',
				'basePath' => APPLICATION_PATH 
		) );
		return $modelLoader;
	}
	
	public function _initPlugins(){
		$zf = Zend_Controller_Front::getInstance();
		$zf->registerPlugin(new Plugin_Loadhead());
	}
	
	public function _initDojoSupport(){
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		$viewRenderer->setView($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
	}
}