<?php

namespace SERVERSOAP\implement\core;

use SERVERSOAP\abstracts\message\SoapMessage;

/**
 * Implemeta��o concreta do SOAPrequest
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
class SoapRequest extends SoapMessage {
	
	/**
	 * Fabrica do objeto SoapRequest.
	 *
	 * @param string $content        	
	 * @param string $version        	
	 * @return \SERVERSOAP\implement\core\SoapRequest
	 */
	public static function create($content, $version) {
		$request = new SoapRequest ();
		
		// $content is if unmodified from SoapClient not a php string type!
		$request->setContent ( ( string ) (null === $content ? file_get_contents ( "php://input" ) : $content) );
		$request->setLocation ( self::getCurrentUrl () );
		$request->setAction ( isset ( $_SERVER [SoapMessage::SOAP_ACTION_HEADER] ) ? $_SERVER [SoapMessage::SOAP_ACTION_HEADER] : null );
		$request->setVersion ( $version );
		
		if (isset ( $_SERVER [SoapMessage::CONTENT_TYPE_HEADER] )) {
			$request->setContentType ( $_SERVER [SoapMessage::CONTENT_TYPE_HEADER] );
		} elseif (isset ( $_SERVER [SoapMessage::HTTP_CONTENT_TYPE_HEADER] )) {
			$request->setContentType ( $_SERVER [SoapMessage::HTTP_CONTENT_TYPE_HEADER] );
		}
		
		return $request;
	}
	
	/**
	 * constroi a url para array $_SERVER
	 *
	 * @return string
	 */
	public static function getCurrentUrl() {
		$url = '';
		if (isset ( $_SERVER ['HTTPS'] ) && (strtolower ( $_SERVER ['HTTPS'] ) === 'on' || $_SERVER ['HTTPS'] === '1')) {
			$url .= 'https://';
		} else {
			$url .= 'http://';
		}
		$url .= isset ( $_SERVER ['SERVER_NAME'] ) ? $_SERVER ['SERVER_NAME'] : '';
		if (isset ( $_SERVER ['SERVER_PORT'] ) && $_SERVER ['SERVER_PORT'] != 80) {
			$url .= ":{$_SERVER['SERVER_PORT']}";
		}
		$url .= isset ( $_SERVER ['REQUEST_URI'] ) ? $_SERVER ['REQUEST_URI'] : '';
		return $url;
	}
}