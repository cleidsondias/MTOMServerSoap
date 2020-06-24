<?php

namespace SERVERSOAP\implement\mime\part;

use SERVERSOAP\abstracts\mime\part\Part;
use SERVERSOAP\implement\helper\SOAPhelper;

/**
 * Class for MultiPart
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
class MultiPart extends Part {
	
	/**
	 * attrib for Main Part ContentID
	 *
	 * @var $mainPartContentId
	 */
	protected $mainPartContentId;
	
	/**
	 * attrib for storage part of ContentID
	 *
	 * @var array ();
	 */
	protected $parts = array ();
	
	/**
	 * Constructor of class
	 *
	 * @param string $boundary        	
	 */
	public function __construct($boundary = null) {
		$this->setHeader ( 'MIME-Version', '1.0' );
		$this->setHeader ( 'Content-Type', 'multipart/related' );
		$this->setHeader ( 'Content-Type', 'type', 'text/xml' );
		$this->setHeader ( 'Content-Type', 'charset', 'utf-8' );
		if (is_null ( $boundary )) {
			$boundary = $this->generateBoundary ();
		}
		$this->setHeader ( 'Content-Type', 'boundary', $boundary );
	}
	
	/**
	 * get a Mime message
	 *
	 * @param string $withHeaders        	
	 * @return string
	 */
	public function getMimeMessage($withHeaders = false) {
		$message = ($withHeaders === true) ? $this->generateHeaders () : "";
		// add parts
		foreach ( $this->parts as $part ) {
			$message .= "\r\n" . '--' . $this->getHeader ( 'Content-Type', 'boundary' ) . "\r\n";
			$message .= $part->getMessagePart ();
		}
		$message .= "\r\n" . '--' . $this->getHeader ( 'Content-Type', 'boundary' ) . '--';
		return $message;
	}
	
	/**
	 * Get Headers for Http
	 *
	 * @return multitype:string
	 */
	public function getHeadersForHttp() {
		$allowed = array (
				'Content-Type',
				'Content-Description' 
		);
		$headers = array ();
		foreach ( $this->headers as $fieldName => $value ) {
			if (in_array ( $fieldName, $allowed )) {
				$fieldValue = $this->generateHeaderFieldValue ( $value );
				// for http only ISO-8859-1
				$headers [] = $fieldName . ': ' . iconv ( 'utf-8', 'ISO-8859-1//TRANSLIT', $fieldValue );
			}
		}
		return $headers;
	}
	
	/**
	 * Add part in content
	 *
	 * @param Part $part        	
	 * @param string $isMain        	
	 */
	public function addPart(Part $part, $isMain = false) {
		$contentId = trim ( $part->getHeader ( 'Content-ID' ), '<>' );
		if ($isMain === true) {
			$this->mainPartContentId = $contentId;
			$this->setHeader ( 'Content-Type', 'start', $part->getHeader ( 'Content-ID' ) );
		}
		$this->parts[$contentId] = $part;
	}
	
	/**
	 * Get part.
	 *
	 * @param string $contentId        	
	 * @return multitype
	 */
	public function getPart($contentId = null) {
		if (is_null ( $contentId )) {
			$contentId = $this->mainPartContentId;
		}
		if (isset ( $this->parts [$contentId] )) {
			return $this->parts [$contentId];
		}
		return null;
	}
	/**
	 * Get parts
	 *
	 * @param string $includeMainPart        	
	 * @return array ()
	 */
	public function getParts($includeMainPart = false) {
		if ($includeMainPart === true) {
			$parts = $this->parts;
		} else {
			$parts = array ();
			foreach ( $this->parts as $cid => $part ) {
				if ($cid != $this->mainPartContentId) {
					$parts [$cid] = $part;
				}
			}
		}
		return $parts;
	}
	
	/**
	 * Generate boundary
	 *
	 * @return string
	 */
	protected function generateBoundary() {
		return 'urn:uuid:' . SOAPhelper::generateUUID ();
	}
}