<?php

namespace SERVERSOAP;

require_once 'serversoap/implements/core/SoapResponse.php';
require_once 'serversoap/implements/core/SoapRequest.php';
require_once 'serversoap/implements/converter/SwaTypeConverter.php';
require_once 'serversoap/implements/converter/MtomTypeConverter.php';
require_once 'serversoap/abstracts/converter/TypeConverter.php';
require_once 'serversoap/implements/filter/MimeFilter.php';
require_once 'serversoap/implements/filter/XmlMimeFilter.php';
require_once 'serversoap/implements/helper/SEISOAPhelper.php';
require_once 'serversoap/implements/core/SOAPCore.php';

use SERVERSOAP\implement\helper\SEISOAPhelper;
use SERVERSOAP\implement\filter\MimeFilter;
use SERVERSOAP\implement\filter\XmlMimeFilter;
use SERVERSOAP\implement\converter\SwaTypeConverter;
use SERVERSOAP\implement\converter\MtomTypeConverter;
use SERVERSOAP\implement\core\SoapRequest;
use SERVERSOAP\abstracts\converter\TypeConverter;
use SERVERSOAP\implement\core\SOAPCore;
use SERVERSOAP\implement\core\SoapResponse;

/**
 * Copyright (c) 2014 http://www.ignisinventum.com.br, Cleidson Dias do Nascimento
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * Basead by beSimpleSoap
 *
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 *        
 */
class SEISOAPServer extends \SoapServer {
	
	/**
	 * attrib for SOAP_1_1 ou SOAP_1_2
	 *
	 * @var $soapVersion
	 */
	protected $soapVersion = SOAP_1_1;
	
	/**
	 * core of SEISOAP
	 *
	 * @var SOAPCore
	 */
	private $soapCore = null;
	
	/**
	 * class for config of Mime
	 *
	 * @param array $options        	
	 */
	private function configureMime(array &$options) {
		if (isset ( $options ['attachment_type'] ) && SEISOAPhelper::ATTACHMENTS_TYPE_BASE64 !== $options ['attachment_type']) {
			$mimeFilter = new MimeFilter ( $options ['attachment_type'] );
			$this->soapCore->registerFilter ( $mimeFilter );
			$converter = new TypeConverter ();
			if (SEISOAPhelper::ATTACHMENTS_TYPE_SWA === $options ['attachment_type']) {
				$converter = new SwaTypeConverter ();
			} elseif (SEISOAPhelper::ATTACHMENTS_TYPE_MTOM === $options ['attachment_type']) {
				$xmlMimeFilter = new XmlMimeFilter ( $options ['attachment_type'] );
				$this->soapCore->registerFilter ( $xmlMimeFilter );
				$converter = new MtomTypeConverter ();
			}
			$converter->setSOAPCore ( $this->soapCore );
			
			if (! isset ( $options ['typemap'] )) {
				$options ['typemap'] = array ();
			}
			$options ['typemap'] [] = array (
					'type_name' => $converter->getTypeName (),
					'type_ns' => $converter->getTypeNamespace (),
					'from_xml' => function ($input) use($converter) {
						return $converter->convertXmlToPhp ( $input );
					},
					'to_xml' => function ($input) use($converter) {
						return $converter->convertPhpToXml ( $input );
					} 
			);
		}
	}
	
	/**
	 * construct of class
	 *
	 * @param unknown $wsdl        	
	 * @param array $options        	
	 */
	public function __construct($wsdl, array $options = array()) {
		if (isset ( $options ['soap_version'] )) {
			$this->soapVersion = $options ['soap_version'];
		}
		$this->soapCore = new SOAPCore ();
		$this->configureMime ( $options );
		$options ['exceptions'] = true;
		parent::__construct ( $wsdl, $options );
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see SoapServer::handle()
	 */
	public function handle($request = null) {
		
		// faz um embrulho da request
		$soapRequest = SoapRequest::create ( $request, $this->soapVersion );
		
		// manuseia a requisiчуo atual
		try {
			$soapResponse = $this->handle2 ( $soapRequest );
		} catch ( \SoapFault $fault ) {
			$this->fault ( $fault->faultcode, $fault->faultstring );
		}
		$soapResponse->send ();
	}
	
	/**
	 * handler2 of handler
	 *
	 * @param SoapRequest $soapRequest        	
	 * @return unknown
	 */
	private function handle2(SoapRequest $soapRequest) {
		$this->soapCore->filterRequest ( $soapRequest );
		ob_clean ();
		ob_start ();
		
		parent::handle ( $soapRequest->getContent () );
		
		$response = ob_get_clean ();
		
		$soapResponse = SoapResponse::create ( $response, $soapRequest->getLocation (), $soapRequest->getAction (), $soapRequest->getVersion () );
		
		return $soapResponse;
	}
}

?>