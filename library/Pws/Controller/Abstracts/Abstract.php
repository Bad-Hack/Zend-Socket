<?php
abstract class Pws_Controller_Abstracts_Abstract {
	public $Server;
	protected $_client_id;
	
	public function setOptions(array $options) {
	}
	
	final public function __construct(Pws_Server $Server) {
		$this->Server = $Server;
	}
}