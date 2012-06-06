<?php
class Pws_Controller_Open extends Pws_Controller_Abstracts_OpenAbstract {
	public function onOpen() {
	$ip = long2ip ( $this->Server->wsClients [$this->_client_id] [6] );
	
	$this->Server->log ( "$ip ($this->_client_id) has connected." );
	
	// Send a join notice to everyone but the person who joined
	foreach ( $this->Server->wsClients as $id => $client ) {
		if ($id != $this->_client_id) {
			$this->Server->wsSend ( $id, "Visitor $this->_client_id ($ip) has joined the room." );
		}
	}
}
}