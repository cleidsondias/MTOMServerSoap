<?php

namespace SERVERSOAP\implement\mime;

require_once '/serversoap/implements/mime/part/MultiPart.php';
require_once '/serversoap/implements/mime/part/MimePart.php';
require_once '/serversoap/abstracts/mime/part/Part.php';

use SERVERSOAP\implement\mime\part\MultiPart;
use SERVERSOAP\implement\mime\part\MimePart;
use SERVERSOAP\abstracts\mime\part\Part;

/**
 * Classe que faz o parse do mime
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
class MimeParser {
	
	/**
	 * faz o parse do mime
	 *
	 * @param string $mimeMessage        	
	 * @param array $headers        	
	 * @return \SEISOAP\implementacoes\mime\part\MultiPart
	 */
	public static function parseMimeMessage($mimeMessage, array $headers = array()) {
		$boundary = null;
		$start = null;
		$multipart = new MultiPart ();
		$hitFirstBoundary = false;
		$inHeader = true;
		// add given headers, e.g. coming from HTTP headers
		if (count ( $headers ) > 0) {
			foreach ( $headers as $name => $value ) {
				if ($name == 'Content-Type') {
					self::parseContentTypeHeader ( $multipart, $name, $value );
					$boundary = $multipart->getHeader ( 'Content-Type', 'boundary' );
					$start = $multipart->getHeader ( 'Content-Type', 'start' );
				} else {
					$multipart->setHeader ( $name, $value );
				}
			}
			$inHeader = false;
		}
		$content = '';
		$currentPart = $multipart;
		$lines = preg_split ( "/(\r\n)/", $mimeMessage );
		foreach ( $lines as $line ) {
			// ignore http status code and POST *
			if (substr ( $line, 0, 5 ) == 'HTTP/' || substr ( $line, 0, 4 ) == 'POST') {
				continue;
			}
			if (isset ( $currentHeader )) {
				if (isset ( $line [0] ) && ($line [0] === ' ' || $line [0] === "\t")) {
					$currentHeader .= $line;
					continue;
				}
				if (strpos ( $currentHeader, ':' ) !== false) {
					list ( $headerName, $headerValue ) = explode ( ':', $currentHeader, 2 );
					$headerValue = iconv_mime_decode ( $headerValue, 0, 'utf-8' );
					if (strpos ( $headerValue, ';' ) !== false) {
						self::parseContentTypeHeader ( $currentPart, $headerName, $headerValue );
						$boundary = $multipart->getHeader ( 'Content-Type', 'boundary' );
						$start = $multipart->getHeader ( 'Content-Type', 'start' );
					} else {
						$currentPart->setHeader ( $headerName, trim ( $headerValue ) );
					}
				}
				unset ( $currentHeader );
			}
			if ($inHeader) {
				if (trim ( $line ) == '') {
					$inHeader = false;
					continue;
				}
				$currentHeader = $line;
				continue;
			} else {
				// check if we hit any of the boundaries
				if (strlen ( $line ) > 0 && $line [0] == "-") {
					if (strcmp ( trim ( $line ), '--' . $boundary ) === 0) {
						if ($currentPart instanceof MimePart) {
							$content = substr ( $content, 0, - 2 );
							self::decodeContent ( $currentPart, $content );
							// check if there is a start parameter given, if not set first part
							$isMain = (is_null ( $start ) || $start == $currentPart->getHeader ( 'Content-ID' )) ? true : false;
							if ($isMain === true) {
								$start = $currentPart->getHeader ( 'Content-ID' );
							}
							$multipart->addPart ( $currentPart, $isMain );
						}
						$currentPart = new MimePart ();
						$hitFirstBoundary = true;
						$inHeader = true;
						$content = '';
					} elseif (strcmp ( trim ( $line ), '--' . $boundary . '--' ) === 0) {
						$content = substr ( $content, 0, - 2 );
						self::decodeContent ( $currentPart, $content );
						// check if there is a start parameter given, if not set first part
						$isMain = (is_null ( $start ) || $start == $currentPart->getHeader ( 'Content-ID' )) ? true : false;
						if ($isMain === true) {
							$start = $currentPart->getHeader ( 'Content-ID' );
						}
						$multipart->addPart ( $currentPart, $isMain );
						$content = '';
					}
				} else {
					if ($hitFirstBoundary === false) {
						if (trim ( $line ) != '') {
							$inHeader = true;
							$currentHeader = $line;
							continue;
						}
					}
					$content .= $line . "\r\n";
				}
			}
		}
		return $multipart;
	}
	
	/**
	 * Parse a "Content-Type" header with multiple sub values.
	 * e.g. Content-Type: multipart/related; boundary=boundary; type=text/xml;
	 * start="<123@abc>"
	 *
	 * Based on: https://labs.omniti.com/alexandria/trunk/OmniTI/Mail/Parser.php
	 *
	 * @param \BeSimple\SoapCommon\Mime\PartHeader $part
	 *        	Header part
	 * @param string $headerName
	 *        	Header name
	 * @param string $headerValue
	 *        	Header value
	 *        	
	 * @return null
	 */
	private static function parseContentTypeHeader(Part $part, $headerName, $headerValue) {
		list ( $value, $remainder ) = explode ( ';', $headerValue, 2 );
		$value = trim ( $value );
		$part->setHeader ( $headerName, $value );
		$remainder = trim ( $remainder );
		while ( strlen ( $remainder ) > 0 ) {
			if (! preg_match ( '/^([a-zA-Z0-9_-]+)=(.{1})/', $remainder, $matches )) {
				break;
			}
			$name = $matches [1];
			$delimiter = $matches [2];
			$remainder = substr ( $remainder, strlen ( $name ) + 1 );
			if (! preg_match ( '/([^;]+)(;)?(\s|$)?/', $remainder, $matches )) {
				break;
			}
			$value = rtrim ( $matches [1], ';' );
			if ($delimiter == "'" || $delimiter == '"') {
				$value = trim ( $value, $delimiter );
			}
			$part->setHeader ( $headerName, $name, $value );
			$remainder = substr ( $remainder, strlen ( $matches [0] ) );
		}
	}
	
	/**
	 * Decodes the content of a Mime part.
	 *
	 * @param \BeSimple\SoapCommon\Mime\Part $part
	 *        	Part to add content
	 * @param string $content
	 *        	Content to decode
	 *        	
	 * @return null
	 */
	private static function decodeContent(MimePart $part, $content) {
		$encoding = strtolower ( $part->getHeader ( 'Content-Transfer-Encoding' ) );
		$charset = strtolower ( $part->getHeader ( 'Content-Type', 'charset' ) );
		if ($encoding == MimePart::ENCODING_BASE64) {
			$content = base64_decode ( $content );
		} elseif ($encoding == MimePart::ENCODING_QUOTED_PRINTABLE) {
			$content = quoted_printable_decode ( $content );
		}
		if ($charset != 'utf-8') {
			$content = iconv ( $charset, 'utf-8', $content );
		}
		$part->setContent ( $content );
	}
}