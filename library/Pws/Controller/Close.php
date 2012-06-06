<?php
class Pws_Controller_Close extends Pws_Controller_Abstracts_CloseAbstract {
	public function onClose() {
		$ip = long2ip ( $this->Server->wsClients [$this->_client_id] [6] );
		
		$this->Server->log ( "$ip ({$this->_client_id}) has disconnected." );
		
		// Send a user left notice to everyone in the room
		foreach ( $this->Server->wsClients as $id => $client ) {
			$this->Server->wsSend ( $id, "Visitor {$this->_client_id} ($ip) has left the room." );
		}
	}
}