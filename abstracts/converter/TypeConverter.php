<?php

namespace SERVERSOAP\abstracts\converter;

use SERVERSOAP\interfaces\converter\TypeConverterInterface;
use SERVERSOAP\interfaces\converter\SoapConverterInterface;
use SERVERSOAP\implement\core\SOAPCore;

/**
 *
 * Abstract class for type converter
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
class TypeConverter implements TypeConverterInterface, SoapConverterInterface {
	
	/**
	 * attrib for o SOAPCore.
	 *
	 * @var SOAPCore
	 */
	protected $soapCore = null;
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\interfaces\converter\TypeConverterInterface::getTypeNamespace()
	 */
	public function getTypeNamespace() {
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\interfaces\converter\TypeConverterInterface::getTypeName()
	 */
	public function getTypeName() {
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\interfaces\TypeConverterInterface::convertXmlToPhp()
	 */
	public function convertXmlToPhp($data) {
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\interfaces\converter\TypeConverterInterface::convertPhpToXml()
	 */
	public function convertPhpToXml($data) {
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\interfaces\converter\SoapConverterInterface::setSOAPCore()
	 */
	function setSOAPCore(SOAPCore $soapCore) {
		$this->soapCore = $soapCore;
	}
}

?>