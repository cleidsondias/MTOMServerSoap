<?php

namespace SERVERSOAP\abstracts\mime\part;

/**
 * Abstract ckass for implemetation for Multipart Mime e MimePart
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
abstract class Part {
	
	/**
	 * mime header.
	 *
	 * @var array()
	 */
	protected $headers = array ();
	
	/**
	 * add header type MIME
	 *
	 * @param unknown $name        	
	 * @param unknown $value        	
	 * @param string $subValue        	
	 */
	public function setHeader($name, $value, $subValue = null) {
		if (isset ( $this->headers [$name] ) && ! is_null ( $subValue )) {
			if (! is_array ( $this->headers [$name] )) {
				$this->headers [$name] = array (
						'@' => $this->headers [$name],
						$value => $subValue 
				);
			} else {
				$this->headers [$name] [$value] = $subValue;
			}
		} elseif (isset ( $this->headers [$name] ) && is_array ( $this->headers [$name] ) && isset ( $this->headers [$name] ['@'] )) {
			$this->headers [$name] ['@'] = $value;
		} else {
			$this->headers [$name] = $value;
		}
	}
	
	/**
	 * get mime header.
	 *
	 * @param
	 *        	string
	 * @param
	 *        	string (opcional)
	 * @return array()
	 */
	public function getHeader($name, $subValue = null) {
		if (isset ( $this->headers [$name] )) {
			if (! is_null ( $subValue )) {
				if (is_array ( $this->headers [$name] ) && isset ( $this->headers [$name] [$subValue] )) {
					return $this->headers [$name] [$subValue];
				} else {
					return null;
				}
			} elseif (is_array ( $this->headers [$name] ) && isset ( $this->headers [$name] ['@'] )) {
				return $this->headers [$name] ['@'];
			} else {
				return $this->headers [$name];
			}
		}
		return null;
	}
	
	/**
	 * gera cabeçalhos.
	 *
	 * @return string
	 */
	protected function generateHeaders() {
		$charset = strtolower ( $this->getHeader ( 'Content-Type', 'charset' ) );
		$preferences = array (
				'scheme' => 'Q',
				'input-charset' => 'utf-8',
				'output-charset' => $charset 
		);
		$headers = '';
		foreach ( $this->headers as $fieldName => $value ) {
			$headers .= $fieldName . ': ' . $fieldValue . "\r\n";
		}
		return $headers;
	}
	
	/**
	 * generate header field value
	 *
	 * @param
	 *        	string
	 * @return string
	 */
	protected function generateHeaderFieldValue($value) {
		$fieldValue = '';
		if (is_array ( $value )) {
			if (isset ( $value ['@'] )) {
				$fieldValue .= $value ['@'];
			}
			foreach ( $value as $subName => $subValue ) {
				if ($subName != '@') {
					$fieldValue .= '; ' . $subName . '=' . $this->quoteValueString ( $subValue );
				}
			}
		} else {
			$fieldValue .= $value;
		}
		return $fieldValue;
	}
	
	/**
	 * get quote value.
	 *
	 * @param
	 *        	string
	 * @return string
	 */
	private function quoteValueString($string) {
		if (preg_match ( '~[()<>@,;:\\"/\[\]?=]~', $string )) {
			return '"' . $string . '"';
		} else {
			return $string;
		}
	}
}

?>