<?php

namespace SERVERSOAP\implement\core;

use SERVERSOAP\abstracts\core\AbstractSOAPCore;
use SERVERSOAP\implement\core\SoapResponse;
use SERVERSOAP\implement\core\SoapRequest;

/**
 * Classe do núcleo do SERVERSOAP
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
class SOAPCore extends AbstractSOAPCore {
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SERVERSOAP\abstracts\core\AbstractSOAPCore::filterRequest()
	 */
	public function filterRequest(SoapRequest $request) {
		parent::filterRequest ( $request );
		
		$this->attachments = $request->getAttachments ();
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SERVERSOAP\abstracts\core\AbstractSOAPCore::filterResponse()
	 */
	public function filterResponse(SoapResponse $response) {
		$response->setAttachments ( $this->attachments );
		$this->attachments = array ();
		
		parent::filterResponse ( $response );
	}
}

?>