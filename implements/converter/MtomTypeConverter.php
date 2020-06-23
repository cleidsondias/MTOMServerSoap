<?php

namespace SERVERSOAP\implement\converter;

use SERVERSOAP\abstracts\converter\TypeConverter;
use SERVERSOAP\implement\helper\SOAPhelper;
use SERVERSOAP\implement\mime\part\MimePart;

/**
 *
 * Classe que extende o conversor de typo e especializa para o MTOM.
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
class MtomTypeConverter extends TypeConverter {
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\abstracoes\converter\TypeConverter::getTypeNamespace()
	 */
	public function getTypeNamespace() {
		return 'http://www.w3.org/2001/XMLSchema';
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\abstracoes\converter\TypeConverter::getTypeName()
	 */
	public function getTypeName() {
		return 'base64Binary';
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\abstracoes\converter\TypeConverter::convertXmlToPhp()
	 */
	public function convertXmlToPhp($data) {
		$doc = new \DOMDocument ();
		$doc->loadXML ( $data );
		$includes = $doc->getElementsByTagNameNS ( SOAPhelper::NS_XOP, 'Include' );
		$include = $includes->item ( 0 );
		
		// convert href -> myhref for external references as PHP throws exception in this case
		// http://svn.php.net/viewvc/php/php-src/branches/PHP_5_4/ext/soap/php_encoding.c?view=markup#l3436
		if (isset ( $include )) {
			$ref = $include->getAttribute ( 'myhref' );
			
			if ('cid:' === substr ( $ref, 0, 4 )) {
				$contentId = urldecode ( substr ( $ref, 4 ) );
				
				if (null !== ($part = $this->soapCore->getAttachment ( $contentId ))) {
					
					return $part->getContent ();
				} else {
					
					return null;
				}
			}
		}
		return $data;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\abstracoes\converter\TypeConverter::convertPhpToXml()
	 */
	public function convertPhpToXml($data) {
		$part = new MimePart ( $data );
		$contentId = trim ( $part->getHeader ( 'Content-ID' ), '<>' );
		
		$this->soapCore->addAttachment ( $part );
		
		$doc = new \DOMDocument ();
		$node = $doc->createElement ( $this->getTypeName () );
		$doc->appendChild ( $node );
		
		// add xop:Include element
		$xinclude = $doc->createElementNS ( SOAPhelper::NS_XOP, SOAPhelper::PFX_XOP . ':Include' );
		$xinclude->setAttribute ( 'href', 'cid:' . $contentId );
		$node->appendChild ( $xinclude );
		
		return $doc->saveXML ();
	}
}