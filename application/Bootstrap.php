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
}