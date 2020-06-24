<?php

namespace SERVERSOAP\interfaces\filter;

use SERVERSOAP\implement\core\SoapResponse;

/**
 * Interface for response filter
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
interface SoapResponseFilter {
	/**
	 *
	 * @param SoapResponse $response        	
	 */
	public function filterResponse(SoapResponse $response);
}