<?php

namespace SERVERSOAP\interfaces\converter;

/**
 * Interface de conversão de tipos
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
interface TypeConverterInterface {
	/**
	 * obtem type namespace.
	 *
	 * @return string
	 */
	function getTypeNamespace();
	
	/**
	 * obtem type name.
	 *
	 * @return string
	 *
	 */
	function getTypeName();
	
	/**
	 * convert a string XML para o PHP.
	 *
	 * @param string $data
	 *        	XML string
	 *        	
	 * @return mixed
	 *
	 */
	function convertXmlToPhp($data);
	
	/**
	 * convert o PHP para uma string XML.
	 *
	 * @param mixed $data
	 *        	PHP type
	 *        	
	 * @return string
	 *
	 */
	function convertPhpToXml($data);
}
?>