<?php

namespace SERVERSOAP\implement\core;

use SERVERSOAP\abstracts\message\SoapMessage;

/**
 * implementacao concreta do SOAPResponse
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
class SoapResponse extends SoapMessage {
	
	/**
	 * Fabrica do objeto SoapResponse.
	 *
	 * @param unknown $content        	
	 * @param unknown $location        	
	 * @param unknown $action        	
	 * @param unknown $version        	
	 * @return \SERVERSOAP\implement\core\SoapResponse
	 */
	public static function create($content, $location, $action, $version) {
		$response = new SoapResponse ();
		$response->setContent ( $content );
		$response->setLocation ( $location );
		$response->setAction ( $action );
		$response->setVersion ( $version );
		$contentType = SoapMessage::getContentTypeForVersion ( $version );
		$response->setContentType ( $contentType );
		
		return $response;
	}
	
	/**
	 * envia a resposta SOAP ao cliente.
	 */
	public function send() {
		header ( 'Content-Type: ' . $this->getContentType () );
		
		echo $this->getContent ();
	}
}