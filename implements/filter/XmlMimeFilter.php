<?php

namespace SERVERSOAP\implement\filter;

use SERVERSOAP\interfaces\filter\SoapResponseFilter;
use SERVERSOAP\abstracts\filter\Filter;
use SERVERSOAP\implement\core\SoapResponse;
use SERVERSOAP\implement\helper\SOAPhelper;
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
		$filterHelper->addNamespace ( SOAPhelper::PFX_XMLMIME, SOAPhelper::NS_XMLMIME );
		
		// obtem o elemento xsd:base64binary
		$xpath = new \DOMXPath ( $dom );
		$xpath->registerNamespace ( 'XOP', SOAPhelper::NS_XOP );
		$query = '//XOP:Include/..';
		$nodes = $xpath->query ( $query );
		
		// troca os atributos
		if ($nodes->length > 0) {
			foreach ( $nodes as $node ) {
				if ($node->hasAttribute ( 'contentType' )) {
					$contentType = $node->getAttribute ( 'contentType' );
					$node->removeAttribute ( 'contentType' );
					$filterHelper->setAttribute ( $node, SOAPhelper::NS_XMLMIME, 'contentType', $contentType );
				}
			}
		}
	}
}

?>