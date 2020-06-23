<?php

namespace SERVERSOAP\abstracoes\core;

use SERVERSOAP\implement\core\SoapResponse;
use SERVERSOAP\implement\core\SoapRequest;
use SERVERSOAP\interfaces\filter\SoapResponseFilter;
use SERVERSOAP\interfaces\filter\SoapRequestFilter;
use SERVERSOAP\implement\mime\part\MimePart;

/**
 *
 * Abstract class for SOAPCore
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
abstract class AbstractSOAPCore {
	/**
	 * attachments
	 *
	 * @var array()
	 */
	protected $attachments = array ();
	
	/**
	 * requestFilters
	 *
	 * @var array()
	 */
	private $requestFilters = array ();
	
	/**
	 * responseFilters
	 *
	 * @var array()
	 */
	private $responseFilters = array ();
	
	/**
	 * atacha um objeto;
	 *
	 * @param MimePart $attachment        	
	 */
	public function addAttachment(MimePart $attachment) {
		$contentId = trim ( $attachment->getHeader ( 'Content-ID' ), '<>' );
		
		$this->attachments [$contentId] = $attachment;
	}
	
	/**
	 * obtem o objeto atachado se passado o id do mesmo
	 *
	 * @param string $contentId        	
	 * @return multitype:|NULL
	 */
	public function getAttachment($contentId) {
		if (isset ( $this->attachments [$contentId] )) {
			$part = $this->attachments [$contentId];
			unset ( $this->attachments [$contentId] );
			
			return $part;
		}
		
		return null;
	}
	
	/**
	 * registra o filtro
	 *
	 * @param unknown $filter        	
	 */
	public function registerFilter($filter) {
		if ($filter instanceof SoapRequestFilter) {
			array_unshift ( $this->requestFilters, $filter );
		}
		
		if ($filter instanceof SoapResponseFilter) {
			array_push ( $this->responseFilters, $filter );
		}
	}
	
	/**
	 * Filtra a requisição
	 *
	 * @param SoapRequest $request        	
	 */
	public function filterRequest(SoapRequest $request) {
		foreach ( $this->requestFilters as $filter ) {
			$filter->filterRequest ( $request );
		}
	}
	
	/**
	 * Filtra a resposta
	 *
	 * @param SoapResponse $response        	
	 */
	public function filterResponse(SoapResponse $response) {
		foreach ( $this->responseFilters as $filter ) {
			$filter->filterResponse ( $response );
		}
	}
}

?>