<?php

namespace SERVERSOAP\implement\converter;

require_once '/serversoap/abstracts/converter/TypeConverter.php';

use SERVERSOAP\abstracts\converter\TypeConverter;

/**
 *
 * Classe que extende o conversor de tipo e especializa para o SwA.
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
class SwaTypeConverter extends TypeConverter {
	
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
		
		// convert href -> myhref for external references as PHP throws exception in this case
		// http://svn.php.net/viewvc/php/php-src/branches/PHP_5_4/ext/soap/php_encoding.c?view=markup#l3436
		$ref = $doc->documentElement->getAttribute ( 'myhref' );
		
		if ('cid:' === substr ( $ref, 0, 4 )) {
			$contentId = urldecode ( substr ( $ref, 4 ) );
			
			if (null !== ($part = $this->soapCore->getAttachment ( $contentId ))) {
				return $part->getContent ();
			} else {
				
				return null;
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
		
		return sprintf ( '<%s href="%s"/>', $this->getTypeName (), 'cid:' . $contentId );
	}
}

?>