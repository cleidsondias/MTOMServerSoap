<?php

namespace SERVERSOAP\implement\core;

use SERVERSOAP\abstracoes\core\AbstractSOAPCore;
use SERVERSOAP\implement\core\SoapResponse;
use SERVERSOAP\implement\core\SoapRequest;

/**
 * Classe do núcleo do SEISOAP
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
class SOAPCore extends AbstractSOAPCore {
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\abstracoes\core\SOAPCore::filterRequest()
	 */
	public function filterRequest(SoapRequest $request) {
		parent::filterRequest ( $request );
		
		$this->attachments = $request->getAttachments ();
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\abstracoes\core\SOAPCore::filterResponse()
	 */
	public function filterResponse(SoapResponse $response) {
		$response->setAttachments ( $this->attachments );
		$this->attachments = array ();
		
		parent::filterResponse ( $response );
	}
}

?>