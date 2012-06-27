<?php
/**
 * Including Zend Required Modules
 * This is for making the avaibility of zend default functionality
 */

// Define Shortcut for DIRECTORY_SEPARATOR
defined ( 'DS' ) || define ( 'DS', DIRECTORY_SEPARATOR );

// Define Shortcut for PATH_SEPARATOR
defined ( 'PS' ) || define ( 'PS', PATH_SEPARATOR);

// Define path to application directory
defined ( 'APPLICATION_PATH' ) || define ( 'APPLICATION_PATH', realpath ( dirname ( __FILE__ ) . '/../application' ) );

// Define application environment
defined ( 'APPLICATION_ENV' ) || define ( 'APPLICATION_ENV', (getenv ( 'APPLICATION_ENV' ) ? getenv ( 'APPLICATION_ENV' ) : 'production') );

// Ensure library/ is on include_path
set_include_path ( implode ( PATH_SEPARATOR, array (
		realpath ( APPLICATION_PATH . '/../library' ),
		get_include_path () 
) ) );

/**
 * Zend_Application
 */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application ( APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini' );
$application->bootstrap ();

/**
 * Before starting the server we need to confirm that this file has maximum
 * execution time
 * as this is the socket server it would be necessary to add the time-limit to 0
 * => unlimited
 */
// prevent the server from timing out
set_time_limit ( 0 );

// Creating the Pws_Server
$Server = new Pws_Server ( $application );

// Define socket environment
defined ( 'PWS_ENV' ) || define ( 'PWS_ENV', (getenv ( 'PWS_ENV' ) ? getenv ( 'PWS_ENV' ) : 'development') );

function wsOnMessage($Server, $clientID, $message, $messageLength, $binary) {
	$messageController = new Pws_Controller_Message ( $Server, $clientID );
	$options = array (
			'message' => $message,
			'message_length' => $messageLength,
			'binary' => $binary 
	);
	$messageController->setOptions ( $options );
	try {
		$messageController->beforeMessage ();
		$messageController->onMessage ();
		$messageController->afterMessage ();
	} catch ( Exception $e ) {
		$exception = new Pws_Exception ( $e );
		$error = array (
				'message' => $e->getMessage (),
				'code' => $e->getCode () 
		);
		$messageController->sendMessage ( 'nok', array (), $error );
	}
}

// when a client connects
function wsOnOpen($Server, $clientID) {
	$openController = new Pws_Controller_Open ( $Server, $clientID );
	$openController->beforeOpen ();
	$openController->onOpen ();
	$openController->afterOpen ();
}

// when a client closes or lost connection
function wsOnClose($Server, $clientID, $status) {
	$closeController = new Pws_Controller_Close ( $Server, $clientID );
	$options = array (
			'status' => $status 
	);
	$closeController->setOptions ( $options );
	$closeController->beforeClose ();
	$closeController->onClose ();
	$closeController->afterClose ();
}
$Server->bind ( 'message', 'wsOnMessage' );
$Server->bind ( 'open', 'wsOnOpen' );
$Server->bind ( 'close', 'wsOnClose' );
// for other computers to connect, you will probably need to change this to your
// LAN IP or external IP,
// alternatively use: gethostbyaddr(gethostbyname($_SERVER['SERVER_NAME']))
$Server->wsStartServer ( '192.168.3.253', 9000 );