var ZS = function(url) {
	var callbacks = {};
	this.ws_url = url;
	this.conn;

	this.bind = function(event_name, callback) {
		callbacks[event_name] = callbacks[event_name] || [];
		callbacks[event_name].push(callback);
		return this;// chainable
	};

	this.send = function(event_data) {
		this.conn.send(event_data);
		return this;
	};

	this.connect = function() {
		// For mozilla web-socket
		if (typeof (MozWebSocket) == 'function')
			this.conn = new MozWebSocket(this.ws_url);
		else
			this.conn = new WebSocket(this.ws_url);

		// dispatch to the right handlers
		this.conn.onmessage = function(evt) {
			dispatch('message', evt.data);
		};

		this.conn.onclose = function() {
			dispatch('close', null);
		};
		this.conn.onopen = function() {
			dispatch('open', null);
		};
	};

	this.disconnect = function() {
		this.conn.close();
	};

	var dispatch = function(event_name, message) {
		var chain = callbacks[event_name];
		if (typeof chain == 'undefined')
			return; // no callbacks for this event
		for ( var i = 0; i < chain.length; i++) {
			chain[i](message);
		}
	};
};