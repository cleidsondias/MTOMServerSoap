<?php

namespace SERVERSOAP\implement\filter;

require_once '/serversoap/interfaces/filter/SoapResponseFilter.php';

require_once '/serversoap/abstracts/filter/Filter.php';

require_once '/serversoap/implements/core/SoapResponse.php';

require_once '/serversoap/implements/helper/SEISOAPhelper.php';

require_once '/serversoap/implements/filter/FilterHelper.php';

use SERVERSOAP\interfaces\filter\SoapResponseFilter;
use SERVERSOAP\abstracts\filter\Filter;
use SERVERSOAP\implement\core\SoapResponse;
use SERVERSOAP\implement\helper\SEISOAPhelper;
use SERVERSOAP\implement\filter\FilterHelper;

/**
 * Implement of XMLMimeFiter
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
class XmlMimeFilter extends Filter implements SoapResponseFilter {
	
	/**
	 * Modify the given response XML.
	 *
	 * @param \BeSimple\SoapCommon\SoapResponse $response
	 *        	SOAP request
	 *        	
	 * @return void
	 */
	public function filterResponse(SoapResponse $response) {
		// obtem o \DOMDocument da requisiчуo SOAP
		$dom = $response->getContentDocument ();
		
		// cria o filtro
		$filterHelper = new FilterHelper ( $dom );
		
		// adiciona o namespace se nescessario
		$filterHelper->addNamespace ( SEISOAPhelper::PFX_XMLMIME, SEISOAPhelper::NS_XMLMIME );
		
		// obtem o elemento xsd:base64binary
		$xpath = new \DOMXPath ( $dom );
		$xpath->registerNamespace ( 'XOP', SEISOAPhelper::NS_XOP );
		$query = '//XOP:Include/..';
		$nodes = $xpath->query ( $query );
		
		// troca os atributos
		if ($nodes->length > 0) {
			foreach ( $nodes as $node ) {
				if ($node->hasAttribute ( 'contentType' )) {
					$contentType = $node->getAttribute ( 'contentType' );
					$node->removeAttribute ( 'contentType' );
					$filterHelper->setAttribute ( $node, SEISOAPhelper::NS_XMLMIME, 'contentType', $contentType );
				}
			}
		}
	}
}

?>