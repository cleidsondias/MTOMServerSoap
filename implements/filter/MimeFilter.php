<?php

namespace SERVERSOAP\implement\filter;

require_once '/serversoap/interfaces/filter/SoapRequestFilter.php';
require_once '/serversoap/interfaces/filter/SoapResponseFilter.php';

require_once '/serversoap/abstracts/filter/Filter.php';

require_once '/serversoap/implements/helper/SEISOAPhelper.php';
require_once '/serversoap/implements/core/SoapRequest.php';
require_once '/serversoap/implements/core/SoapResponse.php';

require_once '/serversoap/implements/mime/parser/MimeParser.php';
require_once '/serversoap/implements/mime/part/MultiPart.php';
require_once '/serversoap/implements/mime/part/MimePart.php';

use SERVERSOAP\interfaces\filter\SoapRequestFilter;
use SERVERSOAP\interfaces\filter\SoapResponseFilter;
use SERVERSOAP\abstracts\filter\Filter;
use SERVERSOAP\implement\core\SoapRequest;
use SERVERSOAP\implement\core\SoapResponse;
use SERVERSOAP\implement\mime\MimeParser;
use SERVERSOAP\implement\mime\part\MultiPart;
use SERVERSOAP\implement\mime\part\MimePart;
use SERVERSOAP\implement\helper\SEISOAPhelper;

/**
 * Implementacao do filtro Mimefilter
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
class MimeFilter extends Filter implements SoapRequestFilter, SoapResponseFilter {
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\interfaces\filter\SoapRequestFilter::filterRequest()
	 */
	public function filterRequest(SoapRequest $request) {
		// array to store attachments
		$attachmentsRecieved = array ();
		
		// check content type if it is a multipart mime message
		$requestContentType = $request->getContentType ();
		if (false !== stripos ( $requestContentType, 'multipart/related' )) {
			// parse mime message
			$headers = array (
					'Content-Type' => trim ( $requestContentType ) 
			);
			$multipart = MimeParser::parseMimeMessage ( $request->getContent (), $headers );
			// get soap payload and update SoapResponse object
			$soapPart = $multipart->getPart ();
			// convert href -> myhref for external references as PHP throws exception in this case
			// http://svn.php.net/viewvc/php/php-src/branches/PHP_5_4/ext/soap/php_encoding.c?view=markup#l3436
			$content = preg_replace ( '/href=(?!#)/', 'myhref=', $soapPart->getContent () );
			$request->setContent ( $content );
			$request->setContentType ( $soapPart->getHeader ( 'Content-Type' ) );
			// store attachments
			$attachments = $multipart->getParts ( false );
			foreach ( $attachments as $cid => $attachment ) {
				$attachmentsRecieved [$cid] = $attachment;
			}
		}
		
		// add attachments to response object
		if (count ( $attachmentsRecieved ) > 0) {
			$request->setAttachments ( $attachmentsRecieved );
		}
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \SEISOAP\interfaces\filter\SoapResponseFilter::filterResponse()
	 */
	public function filterResponse(SoapResponse $response) {
		// get attachments from request object
		$attachmentsToSend = $response->getAttachments ();
		
		// build mime message if we have attachments
		if (count ( $attachmentsToSend ) > 0) {
			$multipart = new MultiPart ();
			$soapPart = new MimePart ( $response->getContent (), 'text/xml', 'utf-8', MimePart::ENCODING_EIGHT_BIT );
			$soapVersion = $response->getVersion ();
			// change content type headers for MTOM with SOAP 1.1
			if ($soapVersion == SOAP_1_1 && $this->attachmentType & SEISOAPhelper::ATTACHMENTS_TYPE_MTOM) {
				$multipart->setHeader ( 'Content-Type', 'type', 'application/xop+xml' );
				$multipart->setHeader ( 'Content-Type', 'start-info', 'text/xml' );
				$soapPart->setHeader ( 'Content-Type', 'application/xop+xml' );
				$soapPart->setHeader ( 'Content-Type', 'type', 'text/xml' );
			} 			// change content type headers for SOAP 1.2
			elseif ($soapVersion == SOAP_1_2) {
				$multipart->setHeader ( 'Content-Type', 'type', 'application/soap+xml' );
				$soapPart->setHeader ( 'Content-Type', 'application/soap+xml' );
			}
			$multipart->addPart ( $soapPart, true );
			foreach ( $attachmentsToSend as $cid => $attachment ) {
				$multipart->addPart ( $attachment, false );
			}
			$response->setContent ( $multipart->getMimeMessage () );
			
			$headers = $multipart->getHeadersForHttp ();
			list ( , $contentType ) = explode ( ': ', $headers [0] );
			
			$response->setContentType ( $contentType );
		}
	}
}

?>

