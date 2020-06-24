<?php

namespace SERVERSOAP\interfaces\filter;

use SERVERSOAP\implement\core\SoapRequest;

/**
 * Interface for request filter
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
interface SoapRequestFilter {
	
	/**
	 *
	 * @param SoapRequest $request        	
	 */
	public function filterRequest(SoapRequest $request);
}