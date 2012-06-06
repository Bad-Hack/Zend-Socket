<?php
class Pws_Controller_Message extends Pws_Controller_Abstracts_MessageAbstract {
	public function onMessage() {
		$ip = long2ip ( $this->Server->wsClients [$this->_client_id] [6] );
		
		// check if message length is 0
		if ($this->_message_length == 0) {
			$this->Server->wsClose ( $this->_client_id );
			return;
		}
		
		// The speaker is the only person in the room. Don't let them feel
		// lonely.
		if (sizeof ( $this->Server->wsClients ) == 1) {
			$this->Server->wsSend ( $this->_client_id, "There isn't anyone else in the room, but I'll still listen to you. --Your Trusty Server" );
		} else {
			// Send the message to everyone but the person who said it
			foreach ( $this->Server->wsClients as $id => $client ) {
				if ($id != $this->_client_id) {
					$this->Server->wsSend ( $id, "Visitor {$this->_client_id} ($ip) said \"$this->_message\"" );
				}
			}
		}
	}
}