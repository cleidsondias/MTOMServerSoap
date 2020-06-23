<?php

namespace SERVERSOAP\interfaces\converter;

use SERVERSOAP\implement\core\SOAPCore;

/**
 * Inteface converter for mensagem SOAP
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
interface SoapConverterInterface {
	
	/**
	 * Informa uma instancia de SOAPCore
	 *
	 * @param SOAPCore $soapCore        	
	 */
	function setSOAPCore(SOAPCore $soapCore);
}

?>