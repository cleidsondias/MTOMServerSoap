<?php

namespace SERVERSOAP\implement\helper;

/**
 * Class for helpers of serversoap
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
class SEISOAPhelper {
	
	/**
	 * Constant for base 64
	 *
	 * @var ATTACHMENTS_TYPE_BASE64
	 */
	const ATTACHMENTS_TYPE_BASE64 = 1;
	
	/**
	 * Constant for definition of MTOM
	 *
	 * @var ATTACHMENTS_TYPE_MTOM
	 */
	const ATTACHMENTS_TYPE_MTOM = 2;
	
	/**
	 * Constant for definition of SWA
	 *
	 * @var ATTACHMENTS_TYPE_SWA
	 */
	const ATTACHMENTS_TYPE_SWA = 4;
	
	/**
	 * Constant for XML Mime.
	 */
	const PFX_XMLMIME = 'xmlmime';
	
	/**
	 * Constant for XML Mime Namespace
	 */
	const NS_XMLMIME = 'http://www.w3.org/2004/11/xmlmime';
	
	/**
	 * Constant for XOP Namespace
	 */
	const NS_XOP = 'http://www.w3.org/2004/08/xop/include';
	
	/**
	 * XML-binary Optimized Packaging namespace prefix.
	 */
	const PFX_XOP = 'xop';
	
	/**
	 * Generate UUID
	 *
	 * @see http://de.php.net/manual/en/function.uniqid.php#94959
	 * @return string
	 */
	public static function generateUUID() {
		return sprintf ( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x', 
				// 32 bits for "time_low"
				mt_rand ( 0, 0xffff ), mt_rand ( 0, 0xffff ), 
				// 16 bits for "time_mid"
				mt_rand ( 0, 0xffff ), 
				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 4
				mt_rand ( 0, 0x0fff ) | 0x4000, 
				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				mt_rand ( 0, 0x3fff ) | 0x8000, 
				// 48 bits for "node"
				mt_rand ( 0, 0xffff ), mt_rand ( 0, 0xffff ), mt_rand ( 0, 0xffff ) );
	}
}

?>